/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro table-private.h
*/

#ifndef _TABLEPRIVATE_H
#define _TABLEPRIVATE_H

#include "list-private.h"

struct table_t
{
	struct list_t **bucket;
	int size;
	int maxSize;
};

int hash(struct table_t *table, char *key);

#endif