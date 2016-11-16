/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//              Grupo: 052
//                    Ficheiro data.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#include "data.h"

// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* 
 * Função que cria um novo elemento de dados data_t e reserva a memória
 * necessária, especificada pelo parâmetro size 
 */
struct data_t *data_create(int size)
{
	// verificar possiveis erros
	if(size <= 0)
		return NULL;

	struct data_t *dataStruct 	= (struct data_t *) malloc(sizeof(struct data_t));
	if ( dataStruct != NULL )
	{
		dataStruct->datasize 	= size;
		dataStruct->data 		= malloc(size);
		return dataStruct;
	}
	return NULL;
}

/* 
 * Função idêntica à anterior, mas que inicializa os dados de acordo com
 * o parâmetro data.
 */
struct data_t *data_create2(int size, void * data)
{
	// verificar possiveis erros
	if(size < 0 || data == NULL )
		return NULL;

	struct data_t *dataStruct = (struct data_t *) malloc(sizeof(struct data_t));
	if ( dataStruct != NULL )
	{
		dataStruct->datasize = size;
		dataStruct->data 	 = malloc(dataStruct->datasize);
		memcpy(dataStruct->data, data, dataStruct->datasize);
		return dataStruct;
	}
	return NULL;
}

/*
 * Função que destrói um bloco de dados e liberta toda a memória.
 */
void data_destroy(struct data_t *data)
{
	// verificar possiveis erros
	if( data == NULL || data->data == NULL)
		return;

	free(data->data);
	free(data);
}

/*
 * Função que duplica uma estrutura data_t.
 */
struct data_t *data_dup(struct data_t *data)
{
	// verificar possiveis erros
	if(data == NULL || data->datasize < 0 || data->data == NULL)
		return NULL;

	struct data_t *dataStruct = (struct data_t *) malloc(sizeof(struct data_t));
	if ( dataStruct != NULL )
	{
		dataStruct->datasize = data->datasize;
		dataStruct->data     = malloc(dataStruct->datasize);
		memcpy(dataStruct->data, data->data, data->datasize);
		return dataStruct;
	}
	return NULL;
	
}
