/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro persistence_manager-private.h
*/
#ifndef _PERSISTENCE_MANAGER_PRIVATE_H
#define _PERSISTENCE_MANAGER_PRIVATE_H

#include <stdio.h>
#include "persistence_manager.h"
#include "table-private.h"

struct pmanager_t
{
	FILE *file;
	char *name;
	int size;
	int capacity;
};

#endif