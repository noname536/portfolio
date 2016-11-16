/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro table_skel.c
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
#include "table_skel-private.h"

// Variables
// -----------------------------------------------------------------------------

struct ptable_t *ptable;
struct pmanager_t *manager;

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* Inicia o skeleton da tabela.
 * O main() do servidor deve chamar esta função antes de poder usar a
 * função invoke(). O parâmetro n_lists define o número de listas a
 * serem usadas pela tabela mantida no servidor.
 * Retorna 0 (OK) ou -1 (erro, por exemplo OUT OF MEMORY)
 */
int table_skel_init(char* filename, int capacity, int n_lists)
{
	struct table_t *table = table_create(n_lists);
	manager = pmanager_create(filename, capacity);
	ptable = ptable_open(table, manager);

	if ( table == NULL || manager == NULL || ptable == NULL )
	{
		return -1;
	}
	return 0;
}

/* Libertar toda a memória e recursos alocados pela função anterior.
 */
int table_skel_destroy()
{
	ptable_destroy (ptable);
	return pmanager_destroy(manager);
}

/* Executa uma operação (indicada pelo opcode na msg_in) e retorna o
 * resultado numa mensagem de resposta ou NULL em caso de erro.
 */
struct message_t *invoke(struct message_t *msg_in)
{
	struct message_t *messageToSend = (struct message_t *) malloc(sizeof(struct message_t));
	switch(msg_in->opcode)
	{
		case OC_SIZE:
			messageToSend->content.result = ptable_size(ptable);
			messageToSend->c_type 		  = CT_RESULT;
			messageToSend->opcode 		  = OC_SIZE + 1;
			break;

		case OC_DEL:
			if( !ptable_del(ptable, msg_in->content.key) )
			{
				messageToSend->content.result = OC_OK;
				messageToSend->c_type 		  = CT_RESULT;
				messageToSend->opcode 		  = OC_DEL+1;
			} 
			else
			{
				messageToSend->content.result = OC_ERR_DEL; //ERRO NO DEL
				messageToSend->c_type 		  = CT_RESULT;
				messageToSend->opcode 		  = OC_RT_ERROR;
			}
			break;

		case OC_GET:
			if( !strcmp(msg_in->content.key, "!") )
			{
				messageToSend->content.keys = (char **) ptable_get_keys(ptable);
				messageToSend->c_type 		= CT_KEYS;
				messageToSend->opcode 		= OC_GET + 1;
			}
			else
			{
				messageToSend->content.data     = (struct data_t *) ptable_get(ptable, msg_in->content.key);
				if ( messageToSend->content.data == NULL )
				{
					messageToSend->content.data = (struct data_t *) malloc(sizeof(struct data_t));
					messageToSend->content.data->datasize = 0;
					messageToSend->content.data->data = NULL;
				}
				messageToSend->c_type 	      	= CT_VALUE;
				messageToSend->opcode 			= OC_GET + 1;
			}
			break;

		case OC_UPDATE:
			if( !ptable_update(ptable, msg_in->content.entry->key, msg_in->content.entry->value) )
			{
				messageToSend->content.result = OC_OK;
				messageToSend->c_type 		  = CT_RESULT;
				messageToSend->opcode 		  = OC_UPDATE + 1;
			} 
			else
			{
				messageToSend->content.result 	= OC_ERR_UPDATE; //ERRO NO UPDATE
				messageToSend->c_type 			= CT_RESULT;
				messageToSend->opcode 			= OC_RT_ERROR;
			}
			break;

		case OC_PUT:
			if( !ptable_put(ptable, msg_in->content.entry->key, msg_in->content.entry->value) )
			{
				messageToSend->content.result = OC_OK;
				messageToSend->c_type 		  = CT_RESULT;
				messageToSend->opcode 		  = OC_PUT + 1;
				
			}
			else
			{
				messageToSend->content.result 	= OC_ERR_PUT; //ERRO NO PUT
				messageToSend->c_type 			= CT_RESULT;
				messageToSend->opcode 			= OC_RT_ERROR;
			}
			break;
	}

	return messageToSend;
}