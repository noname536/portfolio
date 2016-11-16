/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//              Grupo: 052
//                    Ficheiro list-private.h
*/

#ifndef _LISTPRIVATE_H
#define _LISTPRIVATE_H

#include "list.h"

struct list_t
{
	int size;
	struct node_t *node;
};

struct node_t
{
	struct entry_t *data;
	struct node_t *next;
};

#endif