/*//////////////////////////////////////////////////////////////////////////////
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//              Grupo: 052
//                    Ficheiro entry.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#include "data.h"
#include "entry.h"


// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* 
 * Função que cria um novo par {chave, valor} (isto é, que inicializa
 * a estrutura e aloca a memória necessária).
 */
struct entry_t *entry_create(char* key, struct data_t *data)
{
	// verificar possiveis erros
	if( key == NULL || data == NULL)
		return NULL;
	
	struct entry_t *entryStruct = (struct entry_t *) malloc(sizeof(struct entry_t));
	if ( entryStruct != NULL ) 
	{
		entryStruct->key	    = strdup(key);
		entryStruct->value 		= data_dup(data);
		return entryStruct;
	}
	return NULL;
}

/* 
 * Função que destrói um par {chave-valor} e liberta toda a memória.
 */
void entry_destroy(struct entry_t *entry)
{
	// verificar possiveis erros
	if(entry == NULL)
		return;

	free(entry->key);
	data_destroy(entry->value);
	free(entry);
}

/* 
 * Função que duplica um par {chave, valor}.
 */
struct entry_t *entry_dup(struct entry_t *entry)
{
	// verificar possiveis erros
	if(entry == NULL)
		return NULL;

	struct entry_t *entryStruct = (struct entry_t *) malloc(sizeof(struct entry_t));
	if ( entryStruct != NULL )
	{
		entryStruct->key = strdup(entry->key);
		entryStruct->value = data_dup(entry->value);

		return entryStruct;
	}
	return NULL;
}