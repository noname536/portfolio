/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro persistence_manager.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#include "persistence_manager.h"
#include "persistence_manager-private.h"
#include "table.h"
#include "table-private.h"
#include "message.h"
#include "message-private.h"
#include "inet.h"

// Variables
// -----------------------------------------------------------------------------

#define BYTES_CHAR 1

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* Cria um gestor de persistência que armazena logs em filename+".log".
 * O parâmetro logsize define o tamanho máximo em bytes que o ficheiro de
 * log pode ter.
 * Note que filename pode ser um path completo. Retorna o pmanager criado
 * ou NULL em caso de erro.
 */
struct pmanager_t *pmanager_create(char *filename, int logsize)
{

	if (filename == NULL || logsize < 0 )
		return NULL;

	struct pmanager_t *manager = (struct pmanager_t *) malloc(sizeof(struct pmanager_t));

	// handle name
	char *fileExtension = ".log";
	manager->name = (char *) malloc(strlen(filename) + strlen(fileExtension) + 1);
	if ( manager->name == NULL )
		return NULL;
	strcat(manager->name, filename);
	strcat(manager->name, fileExtension);

	//handle FILE
	manager->file = fopen(manager->name, "ab+");

	//handle maxsize
	fseek(manager->file, 0, SEEK_END );
	manager->size = ftell(manager->file);
	manager->capacity = logsize;

	return manager;
}

/* Destrói o gestor de persistência pmanager. Retorna 0 se tudo estiver OK
 * ou -1 em caso de erro. Esta função não limpa o ficheiro de log.
 */
int pmanager_destroy(struct pmanager_t *pmanager)
{
	if ( pmanager == NULL )
		return -1;

	int r = fclose(pmanager->file);
	free(pmanager->name);
	free(pmanager);

	return r;
}

/* Apaga o ficheiro de log gerido pelo gestor de persistência.
 * Retorna 0 se tudo estiver OK ou -1 em caso de erro.
 */
int pmanager_destroy_clear(struct pmanager_t *pmanager)
{
	return remove(pmanager->name);
}

/* Retorna 1 caso existam dados no ficheiro de log e 0 caso contrário.
 */
int pmanager_has_data(struct pmanager_t *pmanager)
{
	if ( pmanager == NULL )
		return 0;

	return !(pmanager->size == 0);
}

/* Adiciona uma string msg no fim do ficheiro de log associado a pmanager.
 * Retorna o numero de bytes escritos no log ou -1 em caso de problemas na
 * escrita (e.g., erro no write()), ou no caso em que o tamanho do ficheiro
 * de log após o armazenamento da mensagem seja maior que logsize (neste
 * caso msg não é escrita no log).
 */
int pmanager_log(struct pmanager_t *pmanager, char *msg)
{
	if ( pmanager == NULL || msg == NULL )
		return -1;

	// prepare msg
	int size;
	memcpy(&size, msg, BYTES_INT);
	char *in = (char *) malloc(size + 1);
	memcpy(in, msg + BYTES_INT, size);
	in[size] = '\r'; // mensagens separadas por \a (alarm code)

	// is at full capacity
	if ( (size + pmanager->size) > pmanager->capacity )
		return -1;

	// write to file
	int bytes = fwrite(in, BYTES_CHAR, size + 1, pmanager->file);
	if ( bytes < 0 )
		return -1;

	// add size!
	pmanager->size += bytes;
	if ( fflush(pmanager->file) == EOF )
	{
		perror("[ERRO] fflush");
	}
	printf("   - Current LOG size: %i bytes \n", pmanager->size );
	free(in);
	return bytes;
}

/* Recupera o estado contido no ficheiro de log, na tabela passada como
 * argumento.
 */
int pmanager_fill_state(struct pmanager_t *pmanager,
                        struct table_t *table)
{
	if ( pmanager == NULL || table == NULL )
		return -1;

	// read from file woop
	char *rawData = (char *) malloc(BYTES_CHAR * pmanager->size);
	// write to file
	fseek(pmanager->file, 0, SEEK_SET );
	int bytes = fread(rawData, BYTES_CHAR, pmanager->size, pmanager->file);
	if ( bytes <= 0 )
	{
		perror("[ERRO] fread");
		return -1;
	}

	printf("[LOG] ** A ler o ficheiro de log...\n");
		
	// translate data
	int fileSize = pmanager->size;
	char rawMessage[MAX_MSG];
	int countMessage = 0;
	int i;
	for( i = 0; i < fileSize; i++ )
	{
		
		if ( rawData[i] == '\r' ) // mensagens separadas por \a (alarm code)
		{
			char *buffer = (char *) malloc(countMessage);
			memcpy(buffer, rawMessage, countMessage);
			struct message_t *message = buffer_to_message(buffer, countMessage);
			if ( message == NULL )
			{
				printf("[LOG] *** Mensagem corrupta...\n");
				continue;
			}
			switch(message->opcode)
			{
				case OC_DEL:
					printf("[LOG] *** Feita a operação delete.\n");
					table_del(table, message->content.key);
					break;
				case OC_UPDATE:
					printf("[LOG] *** Feita a operação update.\n");
					table_update(table, message->content.entry->key, message->content.entry->value);
					break;

				case OC_PUT:
					printf("[LOG] *** Feita a operação put.\n");
					table_put(table, message->content.entry->key, message->content.entry->value);
					break;
			}
			countMessage = 0;
			free(buffer);
		}
		else {
			rawMessage[countMessage] = rawData[i];
			countMessage++;
		}
	}
	free(rawData);
	printf("[LOG] ** Ficheiro de log interpretado!\n");
	return 0;
}