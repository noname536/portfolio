/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro client_stub.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <signal.h>

#include "inet.h"
#include "network_client.h"
#include "network_client-private.h"
#include "message.h"
#include "message-private.h"
#include "client_stub-private.h"


// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

struct rtable_t *rtable_bind(const char *address_port)
{

	struct rtable_t *remote_table = (struct rtable_t *) malloc(sizeof(struct rtable_t));
	remote_table->server = network_connect(address_port);
	if ( remote_table->server == NULL )
		return NULL;
	return remote_table;

}

int rtable_unbind(struct rtable_t *rtable)
{

	int erro = network_close(rtable->server);
	free(rtable);

	return erro;
}

int rtable_put(struct rtable_t *rtable, char *key, struct data_t *value)
{

	struct message_t *mensagem = (struct message_t *) malloc(sizeof(struct message_t));
	mensagem -> opcode = OC_PUT;
	mensagem -> c_type = CT_ENTRY;
	mensagem -> content.entry = entry_create(key, value);
	struct message_t *mensagemRecebida = network_send_receive(rtable->server,mensagem);
	
	if(mensagemRecebida->opcode == (mensagem->opcode + 1))
	{
		printf("Foi inserida na tabela do servidor uma entrada com sucesso!\n");
		printf("Valor no result %d \n", mensagemRecebida->content.result );
		free_message(mensagem);
		free_message(mensagemRecebida);
		return 0;
	}
	else
	{
		free_message(mensagem);
		free_message(mensagemRecebida);
		return -1;
	}

}

int rtable_update(struct rtable_t *rtable, char *key, struct data_t *value)
{

	struct message_t *mensagem = (struct message_t *) malloc(sizeof(struct message_t));
	mensagem -> opcode = OC_UPDATE;
	mensagem -> c_type = CT_ENTRY;
	mensagem -> content.entry = entry_create(key, value);
	struct message_t *mensagemRecebida = network_send_receive(rtable->server,mensagem);

	if(mensagemRecebida->opcode == (mensagem->opcode + 1))
	{
		printf("Foi feito uma alteracao na tabela do servidor com sucesso!\n");
		free_message(mensagem);
		free_message(mensagemRecebida);
		return 0;
	} 
	else 
	{
		free_message(mensagem);
		free_message(mensagemRecebida);
		return -1;
	}
}
/* Função para obter da tabela remota o valor associado à chave key.
 * Devolve NULL em caso de erro.
 */
struct data_t *rtable_get(struct rtable_t *table, char *key)
{

	struct message_t *mensagem = (struct message_t *) malloc(sizeof(struct message_t));
	mensagem -> opcode = OC_GET;
	mensagem -> c_type = CT_KEY;
	mensagem -> content.key = strdup(key);
	struct message_t *mensagemRecebida = network_send_receive(table->server,mensagem);

	if(mensagemRecebida->opcode == (mensagem->opcode + 1))
	{
		if(mensagemRecebida->c_type == CT_VALUE)
		{
			printf("Foi encontrada a chave: %s\n", (mensagemRecebida->content.data->datasize > 0 ? "sim" : "nao") );
			if ( mensagemRecebida->content.data->datasize > 0 )
			{
				printf("Valor associado com essa chave: %s\n", (char *)mensagemRecebida->content.data->data );
			}
			free_message(mensagem);
			struct data_t *data = data_dup(mensagemRecebida->content.data);
			free_message(mensagemRecebida);
			return data;
		}
	}
	free_message(mensagem);
	return NULL;
}

/* Função para remover um par chave valor da tabela remota, especificado 
 * pela chave key.
 * Devolve: 0 (OK) ou -1 em caso de erros.
 */
int rtable_del(struct rtable_t *table, char *key){

	struct message_t *mensagem = (struct message_t *) malloc(sizeof(struct message_t));
	mensagem->opcode = OC_DEL;
	mensagem->c_type = CT_KEY;
	mensagem->content.key = strdup(key);
	struct message_t *mensagemRecebida = network_send_receive(table->server,mensagem);
	if(mensagemRecebida->opcode == (mensagem->opcode +1))
	{
		printf("Foi feito um delete na tabela do servidor com sucesso!\n");
		free_message(mensagem);
		free_message(mensagemRecebida);
		return 0;
	} 
	else 
	{
		free_message(mensagem);
		free_message(mensagemRecebida);
		return -1;
	}

}

/* Devolve número de elementos na tabela remota.
 */
int rtable_size(struct rtable_t *rtable){

	struct message_t *mensagem = (struct message_t *) malloc(sizeof(struct message_t));
	mensagem -> opcode = OC_SIZE;
	mensagem -> c_type = CT_RESULT;
	mensagem -> content.result = (int)NULL;
	int size;
	struct message_t *mensagemRecebida = network_send_receive(rtable->server,mensagem);

	if(mensagemRecebida -> opcode == mensagem -> opcode +1)
	{
		printf("Tamanho da tabela do servidor: %i\n", mensagemRecebida->content.result );
		size = mensagem ->content.result;
		free_message(mensagem);
		free_message(mensagemRecebida);
		return 0;
	} 
	else
	{
		free_message(mensagem);
		free_message(mensagemRecebida);
		return -1;
	}

}

/* Devolve um array de char * com a cópia de todas as keys da
 * tabela remota, e um último elemento a NULL.
 */
char **rtable_get_keys(struct rtable_t *rtable){

	struct message_t *mensagem = (struct message_t *) malloc(sizeof(struct message_t));
	mensagem->opcode = OC_GET;
	mensagem->c_type = CT_KEY;
	mensagem->content.key = strdup("!");
	struct message_t *mensagemRecebida = network_send_receive(rtable->server, mensagem);
	if(mensagemRecebida -> opcode == (mensagem->opcode + 1))
	{
		if(mensagemRecebida->c_type == CT_KEYS)
		{
			char **keys = mensagemRecebida->content.keys;
			int count = 0;
			while( keys[count] != NULL )
			{
				count++;
			}
			char **sendKeys = (char **) malloc((sizeof(char *) * count) + 1);
			int i = 0;
			for( ; i < count; i++ )
			{
				sendKeys[i] = strdup(keys[i]);
			}
			sendKeys[count] = NULL;
			free_message(mensagem);
			free_message(mensagemRecebida);
			return sendKeys; 
		}
	}
	free_message(mensagem);
	free_message(mensagemRecebida);
	return NULL;
}

/* Liberta a memória alocada por table_get_keys().
 */
void rtable_free_keys(char **keys){
	if ( keys == NULL || *keys == NULL )
		return;

	int out = 0;
	while(keys[out] != NULL )
	{
		free(keys[out]);
		out++;
	}
	free(keys);
}


