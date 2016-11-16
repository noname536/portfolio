/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro network_client-private.h
*/

#ifndef _NETWORK_CLIENT_PRIVATE_H
#define _NETWORK_CLIENT_PRIVATE_H

#include "message-private.h"
#include "network_client.h"
#include "inet.h"

#define RETRY_TIME 6 //tempo que a funcao vai dormir


struct server_t
{	
	int socket;
	struct sockaddr_in *info;
}; 

#endif
