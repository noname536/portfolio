package sc.grupo3.fcul;


import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;

/**
 * Classe interna que representa uma operacao feita pelo servidor
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
final class Task {

    private static UserFile fileToUpload;

    /**
     * Autentica o utilizador no sistema
     * @param user User utilizador a ser autenticado
     * @param password String password do utilizador a verificar
     * @param send NetworkData dados com as respostas
     * @return boolean foi autenticado ou nao
     */
    static boolean authenticate(User user, String password, NetworkData send) {
        boolean logged;
        if ( user.exists() ) {
            logged = user.login(password);
            if ( !logged )
                send.put("-p", "Erro - Password errada.");
            else
                send.put("-p", "Autenticacao realizada com sucesso.");
        }
        else {
            user.register(password);
            send.put("-p", "Utilizador " + user.getName() + " registado no sistema com sucesso.");
            logged = true;
        }
        return logged;
    }
    
    /**
     * Envia uma mensagem
     * @param m Message mensagem a ser enviada
     * @param send NetworkData dados com as respostas
     */
    static void sendMessage(Message m, NetworkData send) {
        if ( m.send() ) {
            send.put("-m", "A mensagem [" + m.getMessage() + "] foi enviada com sucesso para ["
                    + m.getTo().getName() + "].");

        }
        else
            send.put("-m", "A mensagem nao pode ser enviada. (O contacto nao existe ou nao pertence ao grupo)");
    }

    /**
     * Adiciona um utilizador a um grupo ou cria um grupo
     * @param group Group grupo a ser criado/inserido em
     * @param owner User dono do grupo
     * @param user User utilizador a ser inserido
     * @param send NetworkData dados com as respostas
     */
    static void addToGroup(Group group, User owner, User user, NetworkData send) {
        if ( !user.exists() ) {
            send.put("-a", "O utilizador " + user.getName() + " nao existe.");
        }
        else {
            StringBuilder sb = new StringBuilder();
            if ( !group.exists() ) {
                group.register(owner);
                group.register(user);
                sb.append("Grupo ").append(group.getName()).append(" foi criado pelo utilizador ").append(owner.getName())
                        .append("\n")
                        .append("O utilizador ").append(user.getName()).append(" foi adicionado ao grupo.");
                send.put("-a", sb.toString());
            }
            else {
                if ( group.isOwner(owner) ) {
                    if (group.register(user)) {
                        sb.append("O utilizador ").append(user.getName()).append(" foi adicionado ao grupo.");
                        send.put("-a", sb.toString());
                    }
                    else {
                        sb.append("O utilizador ").append(user.getName()).append(" ja pertence a este grupo.");
                        send.put("-a", sb.toString());
                    }
                }
                else
                    send.put("-a", "Precisa de ser o dono do grupo para adicionar utilizadores.");
            }
        }
    }

    /**
     * Remove um utilizador de um grupo ou o grupo inteiro
     * @param group Group grupo a ser removido/remover de
     * @param owner User dono do grupo
     * @param user User utilizador a ser removido
     * @param send NetworkData dados com as respostas
     */
    static void removeFromGroup(Group group, User owner, User user, NetworkData send) {
        if ( !user.exists() ) {
            send.put("-d", "O utilizador " + user.getName() + " nao existe.");
        }
        else if ( !group.exists() ) {
            send.put("-d", "O grupo " + group.getName() + " nao existe.");
        }
        else {
            StringBuilder sb = new StringBuilder();
            if ( group.isOwner(owner) ) {
                if ( user.getName().equals(owner.getName()) ) {
                    group.clear();
                    sb.append("O grupo inteiro e suas mensagens/ficheiros foram removidos.");
                    send.put("-d", sb.toString());
                }
                else {
                    if ( group.remove(user) ) {
                        sb.append("O utilizador ").append(user.getName()).append(" foi removido do grupo.");
                        send.put("-d", sb.toString());
                    }
                    else {
                        sb.append("O utilizador ").append(user.getName()).append(" nao pertence a este grupo.");
                        send.put("-d", sb.toString());
                    }
                }
            }
            else
                send.put("-d", "Precisa de ser o dono do grupo para remover utilizadores.");
        }
    }

    /**
     * Inicia a transferencia de um ficheiro vindo do cliente
     * @param user User utilizador que envia o ficheiro
     * @param to Contact contacto distino
     * @param fileName String nome do ficheiro
     * @param fileSize long tamanho total de bytes do ficheiro
     * @param send NetworkData dados com as respostas
     * @return boolean foi possivel iniciar ou nao
     */
	static boolean uploadFile(User user, Contact to, String fileName,
                              long fileSize, NetworkData send) {
        if ( !to.exists() ) {
            send.put("-f", "O utilizador " + to.getName() + " nao existe.");
            return false;
        }
        else {
            fileToUpload = new UserFile(user, to, fileName, fileSize);
            send.put("request", "send_file");
            return true;
        }
	}

    /**
     * Transfere o ficheiro vindo do cliente para o sistema
     * @param rawBytes bytes[] bytes do ficheiro
     * @return long bytes que faltam
     */
    static long writeFile(byte[] rawBytes) {
        return ServerFilesManager.INSTANCE.uploadUserFile(fileToUpload, rawBytes);
    }

    /**
     * Envia todas as mensagems mais recentes trocadas entre um utilizador e outros contactos
     * @param from User utilizador
     * @param send NetworkData dados com as respostas
     */
    static void getAllContactsRecentInfo(User from, NetworkData send) {
        Message[] allMessages = ServerFilesManager.INSTANCE.getMessages(from, null);
        StringBuilder sb = new StringBuilder();

        if ( allMessages != null ) {
            ArrayList<String> allContacts = new ArrayList<>();
            for( Message m : allMessages ) {
                if ( !allContacts.contains(m.getTo().getName()) )
                    allContacts.add(m.getTo().getName());
            }

            for( String contact : allContacts ) {
                ArrayList<Message> groupedMessages = new ArrayList<>();
                for( Message m : allMessages ) {
                    if ( m.getTo().getName().equals(contact) )
                        groupedMessages.add(m);
                }

                Collections.sort(groupedMessages);
                Collections.reverse(groupedMessages);
                
                Message mostRecent = groupedMessages.get(0);
                sb.append("========================\n");
                sb.append("Contact: ").append(mostRecent.getTo().getName());
                sb.append("\n");
                sb.append("me: ").append(mostRecent.getMessage());
                sb.append("\n");
                sb.append(mostRecent.getTime());
                sb.append("\n");
            }

            send.put("-r", sb.toString());
        }
    }
    /**
     * Envia todas as mensagens trocadas entre dois contactos
     * @param from User utilizador
     * @param to Contact contacto distino
     * @param send NetworkData dados com as respostas
     */
    static void getContactInfo(User from, Contact to, NetworkData send ) {
        if ( !to.exists() ) {
            send.put("-r", "O utilizador " + to.getName() + " nao existe.");
        }
        else {
            Message[] allMessagesToContact = ServerFilesManager.INSTANCE.getMessages(from, to);
            Message[] allMessagesFromContact = null;
            // o contacto e um grupo?
            if ( to instanceof Group ) {
                if ( !((Group) to).userExists(from) ) {
                    send.put("-r", "Nao pertence a este grupo. Nao pode ver as mensagens.");
                    return;
                }
                // obter todas as mensagens de todas as pessoas pertencentes a esse grupo
                allMessagesFromContact = ServerFilesManager.INSTANCE.getMessagesFromGroup((Group) to, from);
            }
            // o contacto e um utilizador normal?
            else if ( to instanceof User ) {
                allMessagesFromContact = ServerFilesManager.INSTANCE.getMessages((User) to, from);
            }
            
            ArrayList<Message> allMessages = new ArrayList<>();
            if ( allMessagesFromContact != null)
            	allMessages.addAll(Arrays.asList(allMessagesFromContact));
            if ( allMessagesToContact != null)
            	allMessages.addAll(Arrays.asList(allMessagesToContact));
            Collections.sort(allMessages);
            
            StringBuilder sb = new StringBuilder();
            sb.append("========================\n");
            sb.append("Contact: ").append(to.getName());
            sb.append("\n");
            sb.append("========================\n");
            for (Message m : allMessages) {
                if ( m.getFrom().getName().equals(from.getName()))
                    sb.append("me: ").append(m.getMessage());
                else
                    sb.append(m.getFrom().getName()).append(": ").append(m.getMessage());
                sb.append("\n");
                sb.append(m.getTime());
                sb.append("\n");
            }
            send.put("-r", sb.toString());
        }
    }

    /**
     * Envia um ficheiro para o cliente
     * @param user User utilizador
     * @param from Contact contacto distino
     * @param fileName String nome do ficheiro
     * @param send NetworkData dados com as respostas
     */
    static File downloadFile(User user, Contact from, String fileName, NetworkData send) {
        StringBuilder sb = new StringBuilder();
        sb.append("receive_file").append(":");
        File downloadFile = null;
        if ( !from.exists() ) {
            sb.append("error:O utilizador ").append(from.getName()).append(" nao existe.");
        }
        else {
            downloadFile = ServerFilesManager.INSTANCE.getRecentFileFromTo(from, user, fileName);
            if (downloadFile == null) {
                sb.append("error:Esse ficheiro nao existe no servidor.");
            }
            else {
                sb.append("ok:").append(downloadFile.length());

            }
        }
        send.put("request", sb.toString());
        return downloadFile;
    }

}
