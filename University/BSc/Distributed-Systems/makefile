################################################################################
# 	Trabalho Realizado por:
# 		Fernando Alves, 45592                                                   
# 		Filipe Pereira, 44848
# 		Jose Franco,    44914
# 		Grupo 052
################################################################################



CC = gcc
IDIR=include
ODIR=obj
SDIR=src
CFLAGS=-I$(IDIR) -g
_DEPS = data.h entry.h list.h list-private.h message.h message-private.h network_client.h network_client-private.h inet.h client_stub.h client_stub-private.h table_skel.h persistent_table.h persistent_table-private.h persistence_manager.h persistence_manager-private.h table_skel-private.h
DEPS = $(patsubst %,$(IDIR)/%,$(_DEPS))

OBJ_DATA = obj/data.o obj/test_data.o
OBJ_ENTRY = obj/entry.o obj/data.o obj/test_entry.o
OBJ_LIST = obj/list.o obj/entry.o obj/data.o obj/test_list.o
OBJ_TABLE = obj/table.o obj/list.o obj/entry.o obj/data.o obj/test_table.o
OBJ_MESSAGE = obj/message.o obj/list.o obj/entry.o obj/data.o obj/test_message.o
OBJ_TABLE_CLIENTE = obj/message.o obj/list.o obj/entry.o obj/data.o obj/table.o obj/network_client.o obj/table_client.o obj/client_stub.o
OBJ_TABLE_SERVER = obj/message.o obj/list.o obj/entry.o obj/data.o obj/table.o obj/table_server.o obj/persistent_table.o obj/table_skel.o obj/persistence_manager.o

$(ODIR)/%.o: $(SDIR)/%.c $(DEPS)
	gcc -c -o $@ $< $(CFLAGS) -O3

all: data entry list table message table-client table-server

data: $(OBJ_DATA)
	$(CC) -o bin/$@ $^ $(CFLAGS)

entry: $(OBJ_ENTRY)
	$(CC) -o bin/$@ $^ $(CFLAGS)

list: $(OBJ_LIST)
	$(CC) -o bin/$@ $^ $(CFLAGS)

table: $(OBJ_TABLE)
	$(CC) -o bin/$@ $^ $(CFLAGS)

message: $(OBJ_MESSAGE)
	$(CC) -o bin/$@ $^ $(CFLAGS)

table-client: $(OBJ_TABLE_CLIENTE)
	$(CC) -o bin/$@ $^ $(CFLAGS)

table-server: $(OBJ_TABLE_SERVER)
	$(CC) -o bin/$@ $^ $(CFLAGS)

