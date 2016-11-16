/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro persistent_table-private.c
*/
#ifndef _PERSISTENT_TABLE_PRIVATE_H
#define _PERSISTENT_TABLE_PRIVATE_H

#include "table-private.h"
#include "persistence_manager-private.h"
#include "persistent_table.h"

#define ACCESS_OPEN 1
#define ACCESS_CLOSED 0

struct ptable_t
{
	int access;
	struct pmanager_t *manager;
	struct table_t *table;
};

#endif