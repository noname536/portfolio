/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro persistent_table.c
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
#include "persistent_table.h"
#include "persistent_table-private.h"

// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* Abre o acesso a uma tabela persistente, passando como parâmetros a
 * tabela a ser mantida em memória e o gestor de persistência a ser usado
 * para manter logs e checkpoints. Retorna a tabela persistente criada ou
 * NULL em caso de erro.
 */
struct ptable_t *ptable_open(struct table_t *table, 
                             struct pmanager_t *pmanager)
{
	struct ptable_t *ptable = (struct ptable_t *) malloc(sizeof(struct ptable_t));
	ptable->table 			= table;
	ptable->manager 		= pmanager;

	if ( pmanager_has_data(ptable->manager) )
	{
		pmanager_fill_state(ptable->manager, ptable->table);
	}
	ptable->access 		 	= ACCESS_OPEN;

	return ptable;
}

/* Fecha o acesso a uma tabela persistente. Todas as operações em table
 * devem falhar após um ptable_close.
 */
void ptable_close(struct ptable_t *ptable)
{
	ptable->access = ACCESS_CLOSED;
}

/* Liberta toda a memória e apaga todos os ficheiros utilizados pela
 * tabela persistente.
 */
void ptable_destroy(struct ptable_t *ptable)
{
	table_destroy(ptable->table);
	pmanager_destroy_clear(ptable->manager);
	pmanager_destroy(ptable->manager);
	free(ptable);
}

/* Função para adicionar um par chave valor na tabela.
 * Devolve 0 (ok) ou -1 (problemas).
 */
int ptable_put(struct ptable_t *ptable, char *key, struct data_t *value)
{
	if ( ptable->access == ACCESS_OPEN )
	{
		if ( !table_put(ptable->table, key, value) )
		{
			struct message_t *out = (struct message_t *)
									malloc(sizeof(struct message_t));
			out->opcode = OC_PUT;
			out->c_type = CT_ENTRY;
			out->content.entry = entry_create(key, value);
			char *buf;
			int msgSize = message_to_buffer(out, &buf);
			char *newBuf = (char *) malloc(msgSize + BYTES_INT);
			memcpy(newBuf, &msgSize, BYTES_INT);
			memcpy(newBuf + BYTES_INT, buf, msgSize);
			if ( pmanager_log(ptable->manager, newBuf) < 0 )
			{
				printf("[ERROR] Nao foi possivel escrever no log!\n" );
			}
			return 0;
		}
		else
			return -1;
	}
	return -1;
}

/* Função para substituir na tabela, o valor associado à chave key.
 * Devolve 0 (OK) ou -1 em caso de erros.
 */
int ptable_update(struct ptable_t *ptable, char *key, struct data_t *value)
{
	if ( ptable->access == ACCESS_OPEN )
	{
		if ( !table_update(ptable->table, key, value) )
		{
			struct message_t *out = (struct message_t *)
									malloc(sizeof(struct message_t));
			out->opcode = OC_UPDATE;
			out->c_type = CT_ENTRY;
			out->content.entry = entry_create(key, value);
			char *buf;
			int msgSize = message_to_buffer(out, &buf);
			char *newBuf = (char *) malloc(msgSize + BYTES_INT);
			memcpy(newBuf, &msgSize, BYTES_INT);
			memcpy(newBuf + BYTES_INT, buf, msgSize);
			if ( pmanager_log(ptable->manager, newBuf) < 0 )
			{
				printf("[ERROR] Nao foi possivel escrever no log!\n" );
			}
			return 0;
		}
		else
			return -1;
	}
	return -1;
}

/* Função para obter da tabela o valor associado à chave key.
 * Devolve NULL em caso de erro.
 */
struct data_t *ptable_get(struct ptable_t *ptable, char *key)
{
	if ( ptable->access == ACCESS_OPEN )
	{
		return table_get(ptable->table, key);
	}
	return NULL;
}

/* Função para remover um par chave valor da tabela, especificado pela
 * chave key.
 * Devolve: 0 (OK) ou -1 em caso de erros
 */
int ptable_del(struct ptable_t *ptable, char *key)
{
	if ( ptable->access == ACCESS_OPEN )
	{
		if( !table_del(ptable->table, key) )
		{
			struct message_t *out = (struct message_t *) 
									malloc(sizeof(struct message_t));
			out->opcode = OC_DEL;
			out->c_type = CT_KEY;
			out->content.key = key;
			char *buf;
			int msgSize = message_to_buffer(out, &buf);
			char *newBuf = (char *) malloc(msgSize + BYTES_INT);
			memcpy(newBuf, &msgSize, BYTES_INT);
			memcpy(newBuf + BYTES_INT, buf, msgSize);
			if ( pmanager_log(ptable->manager, newBuf) < 0 )
			{
				printf("[ERROR] Nao foi possivel escrever no log!\n" );
			}
			return 0;
		}
		else
			return -1;
	}
	return -1;
}

/* Devolve número de elementos na tabela.
 */
int ptable_size(struct ptable_t *ptable)
{
	if ( ptable->access == ACCESS_OPEN )
	{
		return table_size(ptable->table);
	}
	return -1;
}

/* Devolve um array de char * com a cópia de todas as keys da tabela
 * e um último elemento a NULL.
 */
char **ptable_get_keys(struct ptable_t *ptable)
{
	if ( ptable->access == ACCESS_OPEN )
	{
		return table_get_keys(ptable->table);
	}
	return NULL;
}

/* Liberta a memória alocada por ptable_get_keys().
 */
void ptable_free_keys(char **keys)
{
	table_free_keys(keys);
}