/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro list.c
*/


// Includes
// -----------------------------------------------------------------------------
#include <stdio.h>
#include <string.h>
#include <stdlib.h>

#include "data.h"
#include "entry.h"
#include "list-private.h"
#include "list.h"

// Variables
// -----------------------------------------------------------------------------

// Main
// -----------------------------------------------------------------------------

// Methods
// -----------------------------------------------------------------------------

/* 
 * Cria uma nova lista. Em caso de erro, retorna NULL.
 */
struct list_t *list_create()
{
	struct list_t *newList  = (struct list_t *) malloc(sizeof(struct list_t));
	if ( newList != NULL ) 
	{
		newList->size 		= 0;
		newList->node 		= NULL;
		return newList;
	}
	return NULL;
}

/* 
 * Elimina uma lista, libertando *toda* a memoria utilizada pela
 * lista.
 */
void list_destroy(struct list_t *list)
{
	// verificar possiveis erros
	if(list == NULL)
		return ;

	struct node_t *currentNode = list->node;
	struct node_t *backupNode;
	while(currentNode != NULL )
	{
		entry_destroy(currentNode->data);
		backupNode = currentNode->next;
		free(currentNode);
		if ( backupNode == NULL )
			break;
		else
			currentNode = backupNode;
	}
	free(list);
}


/* 
 * Cria um novo nó a ser adicionado a lista.
 * Retorna NULL se nao for possivel alocar memoria.
 */
struct node_t *list_newNode(struct entry_t *data, struct node_t *next)
{
	struct node_t *new = (struct node_t *) malloc(sizeof(struct node_t));
	new->data 		   = entry_dup(data);
	new->next 		   = next;

	return new;
}

/* 
 * Adiciona uma entry na lista. Como a lista deve ser ordenada, 
 * a nova entry deve ser colocada no local correto.
 * Retorna 0 (OK) ou -1 (erro)
 */
int list_add(struct list_t *list, struct entry_t *entry)
{
	// verificar possiveis erros
	if ( list == NULL || entry == NULL )
		return -1;

	struct node_t *currentNode = list->node;
	struct node_t *previousNode = NULL;
	int finished = 0;

	if ( list->size == 0 )
	{	
		list->node = list_newNode(entry, NULL);
	}
	else
	{
		while( !finished && currentNode != NULL )
		{
			//retirei o cmp para poder verificar logo se ele é igual a uma chave existente
			int cmp = strcmp(currentNode->data->key, entry->key); 
			// fim da lista
			if ( currentNode->next == NULL )
			{
				if ( cmp == 0 )
					return -1;

				currentNode->next = list_newNode(entry, NULL);
				finished = 1;
			}
			else
			{
				// chave actual é maior que a chave que queremos inserir
				if ( cmp > 0 )
				{
					struct node_t *backup = currentNode;
					// if there is a previous node
					if ( previousNode != NULL )
						previousNode->next = list_newNode(entry, backup);
					// must be at the beginning of the list
					else
						list->node = list_newNode(entry, backup);
					finished = 1;
				}
				// chave actual é menor que a chave que queremos inserir
				else if ( cmp < 0 )
				{
					previousNode = currentNode;
					currentNode  = currentNode->next;
				}
				// ambas as chaves sao iguais
				else 
					return -1;
			}
		}
	}
	list->size++;
	return 0;
}

/* 
 * Elimina da lista um elemento com a chave key. 
 * Retorna 0 (OK) ou -1 (erro)
 */
int list_remove(struct list_t *list, char* key)
{

	// verificar possiveis erros
	if ( list == NULL || key == NULL )
		return -1;

	struct node_t *currentNode 		= list->node;
	struct node_t *previousNode		= NULL;
	struct entry_t *selectedEntry   = NULL;

	while( currentNode && abs(strcmp(currentNode->data->key, key)) )
	{
		previousNode = currentNode;

		if ( currentNode->next == NULL )
			return -1;
		else
			currentNode = currentNode->next;
	}
	selectedEntry = currentNode->data;
	// se houver uma entry
	if ( selectedEntry != NULL )
	{
		if ( list->size > 1 )
		{
			// se for o primeiro entry
			if ( previousNode == NULL )
				list->node = currentNode->next;
			else 
				previousNode->next = currentNode->next;

			free(currentNode);
		}
		// se a lista tiver apenas um elemento (ou menos)
		else if ( list->size > 0 )
		{
			free(currentNode);
			list->node = NULL;
		}
		else 
			return -1;

		list->size--;
		entry_destroy(selectedEntry);
	}
	else 
		return -1;
	return 0;
}

/* 
 * Obtem um elemento da lista que corresponda à chave key. 
 * Retorna a referência do elemento na lista (ou seja, uma alteração
 * implica alterar o elemento na lista). 
 */
struct entry_t *list_get(struct list_t *list, char *key)
{
	// verificar possiveis erros
	if ( list == NULL || key == NULL || list->size == 0 )
		return NULL;

	struct node_t *currentNode = list->node;
	struct entry_t *selectedEntry = NULL;

	while( currentNode != NULL ) 
	{
		if ( strcmp(currentNode->data->key, key) == 0 )
			break;

		if ( currentNode->next == NULL )
			return NULL;
		else
			currentNode = currentNode->next;
	}
	selectedEntry = currentNode->data;
	return selectedEntry;
}

/* 
 * Retorna o tamanho (numero de elementos) da lista 
 * Retorna -1 em caso de erro.  
 */
int list_size(struct list_t *list)
{
	// verificar possiveis erros
	if ( list == NULL )
		return -1;

	return list->size;
}

/*
 * Devolve um array de char * com a cópia de todas as keys da
 * tabela, e um último elemento a NULL.
 */
char **list_get_keys(struct list_t *list)
{
	// verificar possiveis erros
	if ( list == NULL )
		return NULL;

    char **allKeys = (char **) malloc((list->size + 1) * sizeof(char*));
    int out = 0;

    struct node_t *currentNode = list->node;
    while( currentNode != NULL )
    {
        allKeys[out] = (char*) malloc(strlen(currentNode->data->key) + 1);
        strcpy(allKeys[out], currentNode->data->key);
        out++;
        if ( currentNode->next == NULL )
        	break;
        else
        	currentNode = currentNode->next;
    }
    allKeys[out] = NULL;
    
    return allKeys;
}

/*
 * Liberta a memoria reservada por list_get_keys.
 */
void list_free_keys(char **keys)
{
	// verificar possiveis erros
	if( keys == NULL || *keys == NULL)
		return;

    int in = 0;
    while( keys[in] != NULL ) {
        free(keys[in]);
        in++;
    }
    free(keys);
}

/* 
 * Representacao textual de uma lista. Imprime todas as suas chaves.
 * UTILIZADO COMO DEBUG.
 */
void list_output(struct list_t *list) 
{
	struct node_t *currentNode = list->node;
	printf("\nList size: %i\n", list->size );
	if ( list_size(list) == 0 )
		return;
	while( currentNode != NULL ) 
	{
		printf( "key: %s \n", currentNode->data->key);
		if ( currentNode->next == NULL )
			break;
		else
			currentNode = currentNode->next;
	}
}