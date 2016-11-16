/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro table_server.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <netinet/in.h>
#include <signal.h>
#include <unistd.h>
#include <error.h>
#include <errno.h>
#include <poll.h>
#include <fcntl.h>
#include <sys/types.h>
#include <sys/socket.h>

#include "inet.h"
#include "network_client.h"
#include "network_client-private.h"
#include "message.h"
#include "message-private.h"
#include "table_skel-private.h"


// Variables
// -----------------------------------------------------------------------------

#define MAX_CLIENTS  10  	// numero de sockets (uma para listening)
#define TIMEOUT      50 	// em milisegundos
#define DEFAULT_SIZE 1024   // tamanho do ficheiro log

int create_socket();
int bind_socket(int socket, int port);
int ignsigpipe();

// Main
// -----------------------------------------------------------------------------

int main(int argc, char *argv[] )
{
	// ignore sigpipe
	if ( ignsigpipe() != 0 )
	{
		perror("[ERROR] Nao foi possivel ignorar o sinal SIGPIPE\n" );
		return -1;
	}

	if ( argc != 4 )
	{	
		printf("[ERROR] Erro: sintaxe de execucao do programa: ./table-server <port> <n array> <filename>");
		return -1;
	}

	int port 	= atoi(argv[1]);
	int n_table = atoi(argv[2]);
	char *filename = argv[3];
    int socket_client, numberfds, validfds, i; 
    struct pollfd sockConnected[MAX_CLIENTS];

	// criacao da tabela
	if ( table_skel_init(argv[3], DEFAULT_SIZE, n_table) < 0 )
	{
		perror("[ERROR] Erro na criacao da tabela");
		return -1;
	}

	// criar o socket
	int socket = create_socket();
	if ( socket < 0 )
	{	
		perror("[ERROR] Erro ao criar socket");
		return -1;
	}

	int optval = 1;
	if (setsockopt(socket, SOL_SOCKET, SO_REUSEADDR, &optval, sizeof(int)) < 0 ) {
		perror("[ERROR] Erro ao fazer setsockopt");
	}

	// bind do socket
	if ( bind_socket(socket, port) < 0 )
	{
		perror("[ERROR] Erro ao fazer bind");
		close(socket);
		return -1;
	}	

	// faz listen no socket
	if ( listen(socket, 0) < 0 ){
        perror("[ERROR] Erro ao executar listen");
        close(socket);
        return -1;
    }

    // todos os fd's a -1
	for (i = 0; i < MAX_CLIENTS; i++)
		sockConnected[i].fd = -1;

	sockConnected[0].fd = socket;  	   // Vamos detetar eventos na welcoming socket
	sockConnected[0].events = POLLIN;  // Vamos esperar ligações nesta socket
	numberfds = 1;
	printf("* Servidor a espera de clientes...\n");

	while(validfds = poll(sockConnected, numberfds, 10) >= 0)
	{
		if(validfds > 0)
		{
			if ((sockConnected[0].revents & POLLIN) && (numberfds < MAX_CLIENTS))
			{
				if ((sockConnected[numberfds].fd = accept(sockConnected[0].fd, NULL, NULL)) > 0)
				{ 
					// Ligação feita ?
					sockConnected[numberfds].events = POLLIN; // Vamos esperar dados nesta socket
					printf("** Cliente ID: %i foi conectado ao servidor com sucesso!\n", numberfds);
					numberfds++;
				}
			}

			for (i = 1; i < numberfds; i++)
			{
				if(sockConnected[i].revents & POLLHUP)
				{
					printf("** Cliente ID: %i disconectou-se do servidor.\n", i);
					close(sockConnected[i].fd);
			   		sockConnected[i].fd = -1;
			   		numberfds--;
					continue;
				}
				if (sockConnected[i].revents & POLLIN) 
				{ 
					// Dados para ler ?
					printf("*** Servidor esta a espera de receber dados do cliente ID: %i...\n", i);

			    	// receber o tamanho da mensagem do cliente
			    	char *lido = (char *) malloc(sizeof(char) * MAX_MSG);
					int outMsgSize;
					int readSizeInfo = read_all(sockConnected[i].fd, &outMsgSize, BYTES_INT);
					if ( readSizeInfo != BYTES_INT )
					{
						// conexao pode ter sido perdida?
						if ( readSizeInfo == 0 )
						{
							printf("** Cliente ID: %i disconectou-se do servidor.\n", i);
							close(sockConnected[i].fd);
							sockConnected[i].fd = -1;
							numberfds--;
						}
						continue;
					}

					outMsgSize = ntohl(outMsgSize);

			    	// receber os dados do cliente
					int readInfo = read_all(sockConnected[i].fd, lido, outMsgSize);
					if ( readInfo != outMsgSize )
					{
						// conexao pode ter sido perdida?
						if ( readSizeInfo == 0 )
						{
							printf("** Cliente ID: %i disconectou-se do servidor.\n", i);
							close(sockConnected[i].fd);
							sockConnected[i].fd = -1;
							numberfds--;
						}
						continue;
					}

		  			printf("   - Mensagem recebida do cliente com sucesso!\n");

					struct message_t *message = buffer_to_message(lido, outMsgSize);
					struct message_t *messageToSend = (struct message_t *) malloc(sizeof(struct message_t));
					messageToSend = invoke(message);

					//
					// enviar informacao ao cliente
					//

					char *buf;
					int bufSize = message_to_buffer(messageToSend, &buf);
					int auxBufSize = htonl(bufSize);

					// enviar tamanho da mensagem ao cliente
					int writeSizeInfo = write_all(sockConnected[i].fd, &auxBufSize, BYTES_INT);
					if ( writeSizeInfo != BYTES_INT )
					{
						perror("[ERROR] Erro ao enviar tamanho do buffer ao cliente");
						close(sockConnected[i].fd);
						sockConnected[i].fd = -1;
						numberfds--;
						continue;
					}

					// enviar mensagem ao cliente
					int writeInfo = write_all(sockConnected[i].fd, buf, bufSize );
					if ( writeInfo != bufSize )
					{
						perror("[ERROR] Erro ao enviar dados ao cliente");
						close(sockConnected[i].fd);
						sockConnected[i].fd = -1;
						numberfds--;
						continue;
					}
					printf("   - Nova mensagem enviada ao cliente...\n");

					free(lido);
					free_message(message);
					free_message(messageToSend);
				}
			}
				
		}
			
	}
	table_skel_destroy();
 	close(socket);
	return 0;
}

// Methods
// -----------------------------------------------------------------------------

int ignsigpipe(){
	struct sigaction s;
	s.sa_handler = SIG_IGN;
	return sigaction(SIGPIPE, &s, NULL);
}

int create_socket()
{
	return socket(AF_INET, SOCK_STREAM, 0);
}

int bind_socket(int socket, int port)
{
	struct sockaddr_in server;

	// Preenche estrutura server para bind
    server.sin_family 		= AF_INET;
    server.sin_port 		= htons(port);
    server.sin_addr.s_addr 	= htonl(INADDR_ANY);
 
    return bind(socket, (struct sockaddr *) &server, sizeof(server));
}