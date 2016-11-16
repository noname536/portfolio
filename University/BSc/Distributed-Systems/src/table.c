/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro table.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#include "list.h"
#include "list-private.h"
#include "table-private.h"

// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* 
 * Função para criar/inicializar uma nova tabela hash, com n  
 * linhas(n = módulo da função hash)
 */
struct table_t *table_create(int n)
{
	if ( n == 0 )
		return NULL;

	struct table_t *newTable = (struct table_t *) malloc(sizeof(struct table_t));
	if ( newTable != NULL )
	{
		newTable->bucket  = (struct list_t **) malloc(sizeof(struct list_t *) * n);
		newTable->size    = 0;
		newTable->maxSize = n;

		int i = 0;
		while ( i < n )
		{
			newTable->bucket[i] = NULL;
			i++;
		}
		return newTable;
	}


	return NULL;
}

/* 
 * Libertar toda a memória ocupada por uma tabela.
 */
void table_destroy(struct table_t *table)
{
	if ( table == NULL )
		return;

	int i = 0;
	while(i < table->maxSize)
	{
		if ( table->bucket[i] != NULL )
			list_destroy(table->bucket[i]);
		i++;
	}
	free(table->bucket);
	free(table);
}

/*
 * Funcao hash
 */
int hash(struct table_t *table, char *key)
{
	if ( table == NULL || key == NULL )
		return -1;

	int sum = 0;
	int i;
	int size = strlen(key);
	// chave com um numero de digitos igual ou menor que 6
	if ( size <= 6 )
	{
		for( i = 0; i < strlen(key); i++ )
		{
			sum += (int)key[i];
		}
	}
	// todas as outras chaves
	else
	{
		sum = (int)key[0]      + (int)key[1]      + 
		      (int)key[2]      + (int)key[size-3] +
		      (int)key[size-2] + (int)key[size-1];

	}
	return sum % table->maxSize;
}


/* 
 * Função para adicionar um par chave-valor na tabela. 
 * Os dados de entrada desta função deverão ser copiados.
 * Devolve 0 (ok) ou -1 (out of memory, outros erros)
 */
int table_put(struct table_t *table, char *key, struct data_t *value)
{
	if ( table == NULL || key == NULL || value == NULL )
		return -1;

	int indice = hash(table, key);
	int errors = 0;
	struct entry_t *entry = entry_create(key, value);
	
	if(entry == NULL)
		return -1;

	if ( table->bucket[indice] == NULL )
		table->bucket[indice] = list_create();

	// trata as colisoes em que as chaves sao iguais
	if ( list_get(table->bucket[indice], key) != NULL )
	{
		// encontrou um igual, halt
		if ( strcmp(list_get(table->bucket[indice], key)->key, key) == 0 )
		{
			entry_destroy(entry);
			return -1;
		}
	}
	
	// adiciona a lista
	errors = list_add(table->bucket[indice], entry);
	entry_destroy(entry);


	if ( errors != 0 )
		return -1;

	table->size++;

	return 0;
}

/* 
 * Função para substituir na tabela, o valor associado à chave key. 
 * Os dados de entrada desta função deverão ser copiados.
 * Devolve 0 (OK) ou -1 (out of memory, outros erros)
 */
int table_update(struct table_t *table, char *key, struct data_t *value)
{
	if ( table == NULL || key == NULL || value == NULL )
		return -1;

	int indice = hash(table, key);
	if ( table->bucket[indice] != NULL )
	{
		struct entry_t *selectedEntry = list_get(table->bucket[indice], key);
		if ( selectedEntry == NULL )
			return -1;

		data_destroy(selectedEntry->value);
		selectedEntry->value = data_dup(value);
	}
	else
		return -1;

	return 0;
}

/* 
 * Função para obter da tabela o valor associado à chave key.
 * A função deve devolver uma cópia dos dados que terão de ser libertados
 * no contexto da função que chamou table_get.
 * Devolve NULL em caso de erro.
 */
struct data_t *table_get(struct table_t *table, char *key)
{
	if ( table == NULL || key == NULL )
		return NULL;

	int indice = hash(table, key);
	if ( table->bucket[indice] != NULL )
	{
		struct entry_t *selectedEntry = list_get(table->bucket[indice], key);
		if ( selectedEntry == NULL )
			return NULL;

		return data_dup(selectedEntry->value);
	}
	return NULL;
}

/* 
 * Função para remover um par chave valor da tabela, especificado 
 * pela chave key, libertando a memória associada a esse par.
 * Devolve: 0 (OK), -1 (nenhum tuplo encontrado; outros erros)
 */
int table_del(struct table_t *table, char *key)
{
	if ( table == NULL || key == NULL )
		return -1;

	int indice = hash(table, key);
	int errors;
	if ( table->bucket[indice] != NULL )
	{
		if ( list_get( table->bucket[indice], key ) == NULL )
			return -1;
		
		errors = list_remove(table->bucket[indice], key);

		if ( errors < 0 )
			return -1;
		table->size--;
	}
	else
		return -1;
	return 0;
}

/* 
 * Devolve o número de elementos na tabela.
 */
int table_size(struct table_t *table)
{
	if ( table == NULL )
		return -1;

	return table->size;
}

/* 
 * Devolve um array de char * com a cópia de todas as keys da tabela,
 * e um último elemento a NULL.
 */
char **table_get_keys(struct table_t *table)
{
	if ( table == NULL )
		return NULL;

	char **keys = (char **) malloc(sizeof(char *) * (table->size + 1));
	int in = 0;
	int i;
	for( i = 0; i < table->maxSize; i++)
	{
		if (table->bucket[i] != NULL)
		{
			char **listKeys = list_get_keys(table->bucket[i]);
			int j = 0;
			while( listKeys[j] != NULL )
			{
				keys[in] = strdup(listKeys[j]);
				in++;
				j++;
			}

			list_free_keys(listKeys);
		}
	}
	keys[in] = NULL;

	return keys;
}

/* 
 * Liberta a memória alocada por table_get_keys().
 */
void table_free_keys(char **keys)
{
	if ( keys == NULL || *keys == NULL )
		return;

	int out = 0;
	while(keys[out] != NULL )
	{
		free(keys[out]);
		out++;
	}
	free(keys);
}

void table_output(struct table_t *table, int indice)
{
	list_output(table->bucket[indice]);
}