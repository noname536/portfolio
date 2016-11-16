/*//////////////////////////////////////////////////////////////////////////////
//
//              Filipe Inacio da Costa Pereira,     44848
//              Jose Paulo Guerreiro Veigas Franco, 44914
//              Fernando Jose Faustino Alves,       45592
//				Grupo: 052
//                    Ficheiro message-private.h
*/

#ifndef _MESSAGEPRIVATE_H
#define _MESSAGEPRIVATE_H

#include <errno.h>
#include "message.h"

#define OC_RT_ERROR 	99

#define OC_OK       	0
#define OC_ERR_PUT      -1
#define OC_ERR_DEL 		-2
#define OC_ERR_UPDATE   -3

#define BYTES_SHORT 2
#define BYTES_INT   4

/*
 * Funcao auxiliar para posicionar correctamente o offset
 */
void *offset_memcpy(void * destination, int offset, const void * source, size_t num);

int write_all(int sock, void *buf, size_t len);

int read_all(int sock, void *buf, size_t len);

#endif

