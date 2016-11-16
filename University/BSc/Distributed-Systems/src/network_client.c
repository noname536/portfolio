/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro network_client.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <netinet/in.h>

#include "inet.h"
#include "network_client.h"
#include "network_client-private.h"
#include "message.h"
#include "message-private.h"



// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* Esta função deve:
 *  - estabelecer a ligação com o servidor;
 *  - address_port é uma string no formato <hostname>:<port>
 *    (exemplo: 10.10.10.10:10000)
 *  - retornar toda a informacão necessária (e.g., descritor da
 *    socket) na estrutura server_t
 */
struct server_t *network_connect(const char *address_port)
{
	if ( address_port == NULL )
		return NULL;
	
	struct server_t *server  = (struct server_t *) 
				 						malloc(sizeof(struct server_t));
    
    char *rawIP              = strdup(address_port);
	char *ip 				 = strtok(rawIP, ":");
	int port 				 = atoi(strtok(NULL, ":"));

	server->socket 			 = socket(AF_INET, SOCK_STREAM, 0);
	if ( server->socket < 0 )
	{
		perror("[ERROR] Erro ao criar socket TCP");
        return NULL;
	}

	int optval = 1;
	if (setsockopt(server->socket, SOL_SOCKET, SO_REUSEADDR, &optval, sizeof(int)) < 0 ) {
		perror("[ERROR] Erro ao fazer setsockopt");
	}

	server->info = (struct sockaddr_in *) malloc(sizeof(struct sockaddr_in));
	server->info->sin_port   = htons(port);
	server->info->sin_family = AF_INET;
	if (inet_pton(AF_INET, ip, &server->info->sin_addr.s_addr) < 1) 
	{
        perror("[ERROR] Erro ao converter IP");
        close(server->socket);
        return NULL;
    }

    // Estabelece conexão com o servidor definido em server->info
    if (connect(server->socket,(struct sockaddr *)server->info, sizeof(*server->info)) < 0) 
    {
        perror("[ERROR] Erro ao conectar-se ao servidor");
        close(server->socket);
        return NULL;
    }

    free(rawIP);

	return server;
}

/* Esta função deve
 * - Obter o descritor da ligação (socket) da estrutura server_t;
 * - enviar a mensagem msg ao servidor;
 * - receber uma resposta do servidor;
 * - retornar a mensagem obtida como resposta ou NULL em caso
 *   de erro.
 */
struct message_t *network_send_receive(struct server_t *server,
                                       struct message_t *msg)
{
	if ( server == NULL || msg == NULL )
		return NULL;

	char *buf;
	int bufSize = message_to_buffer(msg, &buf);
	int auxBufSize = htonl(bufSize);


	// enviar tamanho da mensagem ao servidor
	int writeSizeInfo = write_all(server->socket, &auxBufSize, BYTES_INT);
	int secondWrite;
	if ( writeSizeInfo != BYTES_INT )
	{
		sleep(RETRY_TIME);
		secondWrite = write_all(server->socket, &auxBufSize, BYTES_INT);
		if(secondWrite != BYTES_INT)
		{
			perror("[ERROR] Erro ao enviar tamanho do buffer ao servidor");
			return NULL;
		}
	}


	// enviar mensagem em forma de buffer
	int writeInfo = write_all(server->socket, buf, bufSize);
	secondWrite = 0;
	if ( writeInfo != bufSize )
	{
		//sleep(RETRY_TIME);
		secondWrite = write_all(server->socket, buf, bufSize);
		if(secondWrite != bufSize)
		{
			perror("[ERROR] Erro ao enviar dados ao servidor");
			return NULL;
		}
	}

	// // // // // // // // //
	//
	// espera pela resposta
	//
	// // // // // // // // //


	char *lido = (char *) malloc(sizeof(char) * MAX_MSG);
	int outMsgSize;

	// receber o tamanho da mensagem a ler
	int readSizeInfo = read_all(server->socket, &outMsgSize, BYTES_INT);
	
	if ( readSizeInfo != BYTES_INT )
	{
		perror("[ERROR] Erro ao receber o tamanho do buffer do servidor");
		return NULL;
		
	}

	outMsgSize = ntohl(outMsgSize);

	// receber a mensagem em si em forma de buffer
	int readInfo = read_all(server->socket, lido, outMsgSize);
	if ( readInfo != outMsgSize )
	{
		perror("[ERROR] Erro ao receber dados do servidor");
		return NULL;
	}

	struct message_t *outMsg = buffer_to_message(lido, outMsgSize);

	free(lido);
	free(buf);

	return outMsg;
}

/* A função network_close() deve fechar a ligação estabelecida por
 * network_connect(). Se network_connect() alocou memória, a função
 * deve libertar essa memória.
 */
int network_close(struct server_t *server)
{
	close(server->socket);
	free(server->info);
	free(server);
}