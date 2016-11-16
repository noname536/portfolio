/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro message.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <netinet/in.h>

#include "data.h"
#include "entry.h"
#include "list.h"
#include "list-private.h"
#include "table-private.h"
#include "message.h"
#include "message-private.h"
#include "inet.h"



// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------


/* 
 * Converte o conteúdo de uma message_t num char *, retornando o tamanho do
 * buffer alocado para a mensagem serializada como um array de bytes, ou -1
 * em caso de erro.
 * A mensagem serializada numa sequência de bytes, deve ter o seguinte
 * formato:
 *
 * OPCODE		C_TYPE
 * [2 bytes]	[2 bytes]
 *  
 * a partir daí, o formato difere para cada tipo de conteúdo (c_type):
 * CT_ENTRY		KEYSIZE(KS)		KEY 		DATASIZE(DS)	DATA
 *				[2 bytes]		[KS bytes]	[4 bytes]		[DS bytes]
 * CT_KEY		KEYSIZE(KS)		KEY 		
 *				[2 bytes]		[KS bytes]
 * CT_KEYS		NKEYS			KEYSIZE(KS)	KEY			...
 *				[4 bytes]		[2 bytes]	[KS bytes]	...
 * CT_VALUE		DATASIZE(DS)	DATA
 *				[4 bytes]		[DS bytes]
 * CT_RESULT	RESULT
 *				[4 bytes]
 *
 * Notar que o `\0´ no fim da string e o NULL no fim do array de
 * chaves não são enviados nas mensagens.
 */
int message_to_buffer(struct message_t *msg, char **msg_buf)
{

	if ( msg == NULL ) {
		return -1;
	}

	// Header size
	int headerSize = BYTES_SHORT  // size of OPCODE
				   + BYTES_SHORT; // size of C_TYPE
	// Body size
	int bodySize;

	// alocacao de memoria inicial
	*msg_buf = (char *) malloc(headerSize);
	uint16_t opcode = htons(msg->opcode);
	uint16_t ctype  = htons(msg->c_type);
	int offset      = 0;
	offset_memcpy(*msg_buf, offset, &opcode, BYTES_SHORT);
	offset += BYTES_SHORT;
	offset_memcpy(*msg_buf, offset, &ctype,  BYTES_SHORT);
	offset += BYTES_SHORT;

	// Variables

	char **keys;
	int i; 
	int keysizeSum;
	char *old_buf = NULL;

	// ler o conteudo da mensagem
	switch(msg->c_type)
	{
		case CT_RESULT:
			bodySize = BYTES_INT; 						// ocupa 4 bytes

			old_buf = (char *) realloc(*msg_buf, headerSize + bodySize);
			if ( old_buf == NULL )
				return -1;
			*msg_buf = old_buf;

			uint32_t result = htonl(msg->content.result);
			offset_memcpy(*msg_buf, offset, &result, BYTES_INT);
			break;

		case CT_VALUE:
			bodySize = BYTES_INT						// ocupa 4 bytes
				     + msg->content.data->datasize; 	// ocupa o tamanho do data
			old_buf = (char *) realloc(*msg_buf, headerSize + bodySize);
			if ( old_buf == NULL )
				return -1;
			*msg_buf = old_buf;

			uint32_t datasize = htonl(msg->content.data->datasize);
			offset_memcpy(*msg_buf, offset, &datasize, BYTES_INT);
			offset += BYTES_INT;
			offset_memcpy(*msg_buf, offset, msg->content.data->data, msg->content.data->datasize);

			break;

		case CT_KEY:
			bodySize = BYTES_SHORT						// ocupa 2 bytes
					 + strlen(msg->content.key);		// ocupa o tamanho necessario da key
			old_buf = (char *) realloc(*msg_buf, headerSize + bodySize);
			if ( old_buf == NULL )
				return -1;
			*msg_buf = old_buf;

			uint16_t keysize = htons(strlen(msg->content.key));
			offset_memcpy(*msg_buf, offset, &keysize, BYTES_SHORT);
			offset += BYTES_SHORT;
			offset_memcpy(*msg_buf, offset, msg->content.key, strlen(msg->content.key));
			break;

		case CT_KEYS:
			keys 		    = msg->content.keys;
			i 				= 0;
			keysizeSum 		= 0;

			while( keys[i] != NULL ) {
				keysizeSum += BYTES_SHORT + strlen(keys[i]);
				i++;
			}

			old_buf = (char *) realloc(*msg_buf, headerSize + BYTES_INT + keysizeSum);
			if ( old_buf == NULL )
				return -1;
			*msg_buf = old_buf;

			uint32_t keyNum = htonl(i);
			offset_memcpy(*msg_buf, offset, &keyNum, BYTES_INT);
			offset += BYTES_INT;

			i = 0;

			while( keys[i] != NULL ) {
				uint16_t keysize = htons(strlen(keys[i]));
				offset_memcpy(*msg_buf, offset, &keysize, BYTES_SHORT);
				offset += BYTES_SHORT;
				offset_memcpy(*msg_buf, offset, keys[i], strlen(keys[i]));
				offset += strlen(keys[i]);
				i++;
			}

			bodySize = BYTES_INT						// 4 bytes com o numero de keys
					 + keysizeSum;						// soma total do numero de bytes de todas as keys
			break;

		case CT_ENTRY:
			bodySize = BYTES_SHORT						   	// ocupa 2 bytes
				     + strlen(msg->content.entry->key) 		// ocupa o tamanho da key
				     + BYTES_INT							// ocupa 4 bytes
				     + msg->content.entry->value->datasize; // ocupa o tamanho do data
			old_buf = (char *) realloc(*msg_buf, headerSize + bodySize);
			if ( old_buf == NULL )
				return -1;
			*msg_buf = old_buf;

			uint16_t keySize = htons(strlen(msg->content.entry->key));
			offset_memcpy(*msg_buf, offset, &keySize, BYTES_SHORT);
			offset += BYTES_SHORT;
			offset_memcpy(*msg_buf, offset, msg->content.entry->key, strlen(msg->content.entry->key));
			offset += strlen(msg->content.entry->key);
			uint32_t dataSize = htonl(msg->content.entry->value->datasize);
			offset_memcpy(*msg_buf, offset, &dataSize, BYTES_INT);
			offset += BYTES_INT;
			offset_memcpy(*msg_buf, offset, msg->content.entry->value->data, msg->content.entry->value->datasize);

			break;

		default:
			bodySize = 0;
	}

	if ( *msg_buf == NULL ) {
		return -1;
	}

	return headerSize + bodySize;
}

/* 
 * Transforma uma mensagem no array de bytes, buffer, para
 * uma struct message_t*
 */
struct message_t *buffer_to_message(char *msg_buf, int msg_size)
{	

	if ( msg_buf == NULL || msg_size < 0 ) {
		return NULL;
	}
	
	int opcodeSize = BYTES_SHORT;
	int ctypeSize  = BYTES_SHORT;

	// obter os valores do opcode e do c_type
	uint16_t *opcodeRaw = (uint16_t *) malloc(BYTES_SHORT);
	uint16_t *ctypeRaw  = (uint16_t *) malloc(BYTES_SHORT);
	memcpy(opcodeRaw, msg_buf, opcodeSize);
	memcpy(ctypeRaw, msg_buf + opcodeSize, ctypeSize);
	uint16_t opcode = ntohs(*opcodeRaw);
	uint16_t ctype  = ntohs(*ctypeRaw);
	free(opcodeRaw);
	free(ctypeRaw);

	// inicializar a estrutura
	struct message_t *msgOut = (struct message_t *) malloc(sizeof(struct message_t));
	msgOut->opcode = opcode;
	msgOut->c_type = ctype;

	// variables
	uint32_t *resultRaw;
	uint32_t *datasizeRaw;
	uint16_t *keysizeRaw;
	uint32_t *numKeysRaw;
	uint16_t *keySizeRaw;

	//obter o resto dos valores
	int offset = opcodeSize + ctypeSize;
	switch(ctype)
	{
		case CT_RESULT:
			resultRaw = (uint32_t *) malloc(BYTES_INT);
			memcpy(resultRaw, msg_buf + offset, BYTES_INT);
			uint32_t result = ntohl(*resultRaw);
			
			msgOut->content.result = result;

			free(resultRaw);
			break;

		case CT_VALUE:
			datasizeRaw = (uint32_t *) malloc(BYTES_INT);
			memcpy(datasizeRaw, msg_buf + offset, BYTES_INT);
			uint32_t datasize = ntohl(*datasizeRaw);
			
			offset += BYTES_INT;
			void *data = malloc(datasize);
			memcpy(data, msg_buf + offset, datasize);

			msgOut->content.data = data_create2(datasize, data);

			free(datasizeRaw);
			free(data);
			break;

		case CT_KEY:
			keysizeRaw = (uint16_t *) malloc(BYTES_SHORT);
			memcpy(keysizeRaw, msg_buf + offset, BYTES_SHORT);
			uint16_t keysize = ntohs(*keysizeRaw);
			
			offset += BYTES_SHORT;
			char *key = (char *) malloc(keysize + 1);
			memcpy(key, msg_buf + offset, keysize);
			key[keysize] = '\0';

			msgOut->content.key = strdup(key);

			free(keysizeRaw);
			free(key);
			break;

		case CT_KEYS:
			numKeysRaw = (uint32_t *) malloc(BYTES_INT);
			memcpy(numKeysRaw, msg_buf + offset, BYTES_INT);
			uint32_t numKeys = ntohl(*numKeysRaw);

			char **keys = (char **) malloc(sizeof(char *) * numKeys + 1);

			offset += BYTES_INT;

			int i = 0;
			while( i < numKeys )
			{
				uint16_t *keysizeRaw = (uint16_t *) malloc(BYTES_SHORT);
				memcpy(keysizeRaw, msg_buf + offset, BYTES_SHORT);
				uint16_t keysize = ntohs(*keysizeRaw);
				offset += BYTES_SHORT;
				char *key = (char *) malloc(keysize + 1);
				memcpy(key, msg_buf + offset, keysize);
				key[keysize] = '\0';


				keys[i] = strdup(key);
				offset += keysize;
				i++;

				free(keysizeRaw);
				free(key);
			}
			keys[i] = NULL;

			msgOut->content.keys = keys;

			free(numKeysRaw);

			break;

		case CT_ENTRY:
			keySizeRaw = (uint16_t *) malloc(BYTES_SHORT);
			memcpy(keySizeRaw, msg_buf + offset, BYTES_SHORT);
			uint16_t keysizes = ntohs(*keySizeRaw);

			offset += BYTES_SHORT;

			char *key1 = (char *) malloc(keysizes + 1);
			memcpy(key1, msg_buf + offset, keysizes);
			key1[keysizes] = '\0';

			offset += keysizes;

			uint32_t *dataSizeRaw = (uint32_t *) malloc(BYTES_INT);
			memcpy(dataSizeRaw, msg_buf + offset, BYTES_INT);
			uint32_t dataSize = ntohl(*dataSizeRaw);

			offset += BYTES_INT;

			void *data1 = malloc(dataSize);
			memcpy(data1, msg_buf + offset, dataSize);

			struct data_t *data2 = data_create2(dataSize, data1);
			msgOut->content.entry = entry_create(key1, data2);

			free(keySizeRaw);
			free(key1);
			free(dataSizeRaw);
			free(data1);
			data_destroy(data2);
			break;

		default:
			free(msgOut);
			return NULL;
	}
	return msgOut;
}

/* 
 * Liberta a memoria alocada na função buffer_to_message
 */
void free_message(struct message_t *msg)
{
	if ( msg == NULL ) {
		return;
	}

	switch(msg->c_type)
	{
		case CT_VALUE:
			data_destroy(msg->content.data);
			break;
		case CT_KEY:
			free(msg->content.key);
			break;
		case CT_KEYS:
			list_free_keys(msg->content.keys);
			break;
		case CT_ENTRY:
			entry_destroy(msg->content.entry);
			break;
		default :
			break;
	}

	free(msg);
}

/*
 * Funcao auxiliar para posicionar correctamente o offset
 */
void *offset_memcpy( void * destination, int offset, const void * source, size_t num )
{
	return memcpy(destination + offset, source, num);
}

/*
 * Escreve um bloco de memoria inteiro
 */
int write_all(int sock, void *buf, size_t len) 
{	
	int bufsize = len;
	while( len > 0 ) 
	{
		int res = write(sock, buf, len);
		// conexao foi fechada
		if ( res == 0 )
			return 0; 
		if( res < 0 ) 
		{
			if( errno == EINTR ) 
				continue;
			perror("[ERROR] Write failed");
			return res;
		}
		buf += res;
		len -= res;
	}
	return bufsize;
}

/*
 * Le e verifica um bloco de memoria inteiro
 */
int read_all(int sock, void *buf, size_t len)
{
	int bufsize = len;
	while( len > 0 )
	{
		int lido = read(sock, buf, len);
		// conexao foi fechada
		if ( lido == 0 )
			return 0;
		// erros
		if( lido < 0 )
		{
			if ( errno == EINTR )
				continue;
			perror("[ERROR] Read failed");
			return lido;
		}
		buf += lido;
		len -= lido;
	}
	return bufsize;
}