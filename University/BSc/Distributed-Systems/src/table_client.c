/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro table_client.c
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
#include "client_stub.h"
#include "client_stub-private.h"

// Variables
// -----------------------------------------------------------------------------
// inicializacao de metodos auxiliares
int comando_put(char *argumentos[],struct rtable_t *server);
int comando_get(char *argumentos[],struct rtable_t *server);
int comando_update(char *argumentos[],struct rtable_t *server);
int comando_del(char *argumentos[],struct rtable_t *server);
int comando_size(struct rtable_t *server);

// Main
// -----------------------------------------------------------------------------

int main(int argc, char *argv[])
{

	int erro = 0;
	if ( argc < 2 )
	{
		printf("[ERROR] Erro: sintaxe de execucao do programa: ./table-client <ipv4:port>");
		return -1;
	}

	// connecao ao servidor...
	struct rtable_t *remote_table = rtable_bind(argv[1]);
	if ( remote_table == NULL )
	{
		perror("[ERROR] Nao foi possivel conectar-se ao servidor");
		return -1;
	}

	// lida de comandos de stdin
	while(1)
	{
		char rawLine[128] = {' '};
		printf("Insira comandos a realizar na tabela:\n" );
		if ( fgets(rawLine, 128, stdin) == NULL )
			break;

		char **argumentos = (char **) malloc(sizeof(char *) * 3);
		argumentos[0] = (char *) malloc(sizeof(char) * 100);
		argumentos[1] = (char *) malloc(sizeof(char) * 100);
		int argcount = 0;

		char *tok = strtok(rawLine, " ");

		if ( tok != NULL )
		{
			sscanf(tok, "%s", argumentos[argcount]);
			argcount++;
		}
		
		tok = strtok(NULL, " ");
		if ( tok != NULL )
		{
			sscanf(tok, "%s", argumentos[argcount]);
			argcount++;
		}

		tok = strtok(NULL, "");
		if ( tok != NULL )
		{
			argumentos[2] = tok;
			argcount++;
		}

		
		char *comando = argumentos[0];
		int i;
		// transformar em upper case
		for( i = 0; comando[i] != '\0'; i++ ) 
			comando[i] = toupper( comando[i] );

		if ( !strcmp(comando, "PUT") )
		{
			if ( argcount == 3 )
			{
				if ( comando_put(argumentos, remote_table) < 0 )
				{
					printf("ERRO - Nao foi possivel realizar o PUT\n");
				}
			}
			else
			{
				printf("ERRO - Sintaxe errada, PUT <key> <value>\n" );
				free(argumentos[0]);
				if ( argumentos[1] != NULL )
					free(argumentos[1]);
				free(argumentos);
				continue;
			}
				
		}
		else if ( !strcmp(comando, "GET") )
		{
			if ( argcount == 2 )
			{
				if ( comando_get(argumentos, remote_table) < 0 )
				{
					printf("ERRO - Nao foi encontrada uma chave...\n");
				}
			}
			else
			{
				printf("ERRO - Sintaxe errada, GET <key/!>\n" );
				free(argumentos[0]);
				if ( argumentos[1] != NULL )
					free(argumentos[1]);
				free(argumentos);
				continue;
			}
		}
		else if ( !strcmp(comando, "UPDATE") )
		{
			if ( argcount == 3 )
			{
				if ( comando_update(argumentos, remote_table) < 0 )
				{
					printf("ERRO - Nao foi possivel realizar o UPDATE\n");
				}
			}
			else
			{
				printf("ERRO - Sintaxe errada, UPDATE <key> <value>\n" );
				free(argumentos[0]);
				if ( argumentos[1] != NULL )
					free(argumentos[1]);
				free(argumentos);
				continue;
			}
		}
		else if ( !strcmp(comando, "DEL") )
		{
			if ( argcount == 2 )
			{
				if ( comando_del(argumentos, remote_table) < 0 )
				{
					printf("ERRO - Nao foi possivel realizar o DELETE\n");
				}
			}
			else
			{
				printf("ERRO - Sintaxe errada, DEL <key>\n" );
				free(argumentos[0]);
				if ( argumentos[1] != NULL )
					free(argumentos[1]);
				free(argumentos);
				continue;
			}
		}
		else if ( !strcmp(comando, "SIZE") )
		{
			if ( comando_size(remote_table) < 0 )
			{
				printf("ERRO - Nao foi possivel realizar o SIZE\n");
			}
		}
		else if ( !strcmp(comando, "QUIT") )
		{
			free(argumentos[0]);
			if ( argumentos[1] != NULL )
				free(argumentos[1]);
			free(argumentos);
			break;
		}
		else
		{
			printf("ERRO - Comando nao encontrado.\n" );
			printf("     - Lista de comandos: PUT, GET, UPDATE, DEL, SIZE, QUIT\n" );
			free(argumentos[0]);
			if ( argumentos[1] != NULL )
				free(argumentos[1]);
			free(argumentos);
			continue;
		}
		free(argumentos[0]);
		free(argumentos[1]);
		free(argumentos);
	}

	return rtable_unbind(remote_table);
}

		
// Methods
// -----------------------------------------------------------------------------

/*
 * Executa o comando put
 */
int comando_put(char *argumentos[], struct rtable_t *server)
{

	struct data_t *data = data_create2(strlen(argumentos[2]) + 1, argumentos[2]); // Refeito Done.
	int i = rtable_put(server, argumentos[1], data);
	data_destroy(data);
	return i;
}

/*
 * Executa o comando get
 */
int comando_get(char *argumentos[], struct rtable_t *server)
{
	struct data_t *data;

	if(strcmp(argumentos[1],"!") != 0)
	{
		data = rtable_get(server, argumentos[1]);
		if(data != NULL)
		{
			data_destroy(data);
			return 0;
		}
		else
		{
			return -1;
		}
	}
	else 
	{
		printf("Todas as chaves da tabela:\n");
		int c = 0;
		char **keys = rtable_get_keys(server);
		printf(" - ");
		while(keys[c] != NULL)
		{
			printf("%s ", keys[c] );
			c++;
			if ( c % 8 == 0 )
			{
				printf("\n");
				printf(" - ");
			}
		}
		printf("\n");
		rtable_free_keys(keys);
		return 0;
	}
}

/*
 * Executa o comando update
 */
int comando_update(char *argumentos[], struct rtable_t *server)
{
	struct data_t *data = data_create2(strlen(argumentos[2]) + 1, argumentos[2]);
	int i = rtable_update(server,argumentos[1], data);
	data_destroy(data);
	return i;
}

/*
 * Executa o comando delete
 */
int comando_del(char *argumentos[], struct rtable_t *server)
{
	char *arg = strdup(argumentos[1]);
	int i = rtable_del (server, arg);
	free(arg);
	return i;
}

/*
 * Executa o comando size
 */
int comando_size(struct rtable_t *server)
{	
	return rtable_size(server);	
}