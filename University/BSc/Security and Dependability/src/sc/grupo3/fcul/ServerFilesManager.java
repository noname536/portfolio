package sc.grupo3.fcul;

import java.io.*;
import java.nio.file.Files;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Date;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * Classe singleton responsavel pela manutencao do uso de ficheiros do sistema
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
enum ServerFilesManager {
    INSTANCE;

    // '\u2063' -> unicode special char
    // '0x07'   -> BEL
    // '0x0b'   -> VT
    private static final char CHAR_BEL_DELIM = 0x07;
    private static final char CHAR_VT_DELIM = 0x0b;
    private static final String STRING_DELIM = CHAR_BEL_DELIM + "/" + CHAR_VT_DELIM;
    private static final String REGEX_DELIM = "(" + STRING_DELIM + ")";

    private static final String FILE_USERS    = "data/users.txt";
    private static final String DIR_MESSAGES  = "data/messages/";
    private static final String DIR_GROUPS    = "data/groups/";
    private static final String DIR_UPLOADS   = "data/uploads/";

    private File userFile;

    /**
     * Construtor inteiro no manager
     */
    private ServerFilesManager() {
        this.userFile     = new File(FILE_USERS);
        File messageDir   = new File(DIR_MESSAGES);
        File groupsDir    = new File(DIR_GROUPS);
        File uploadsDir   = new File(DIR_UPLOADS);

        if ( messageDir.mkdirs() )
            System.out.println("*** Servidor criou os directorios para guardar as mensagens.");
        if ( groupsDir.mkdirs() )
            System.out.println("*** Servidor criou os directorios para guardar os grupos.");
        if ( uploadsDir.mkdirs() )
            System.out.println("*** Servidor criou os directorios para guardar os ficheiros.");
    }

    /**
     * Regista no ficheiro de utilizadores um novo utilizador
     * @param user User utilizador a registar
     * @param password String password do utilizador
     * @return boolean se foi possivel registar ou nao
     */
    synchronized final boolean registerUser(User user, String password) {

        boolean ok = true;
        FileOutputStream out = null;

        try {
            out = new FileOutputStream(userFile, true);

            // verifica que um ficheiro existe
            if ( !userFile.exists() )
                ok = userFile.createNewFile();

            // constroi a string: 'user:password'
            StringBuilder sb = new StringBuilder();
            sb.append(user.getName()).append(":").append(password);

            // escreve e fecha o ficheiro
            out.write(sb.toString().getBytes());
            out.write("\n".getBytes());
            out.flush();
            out.close();

        } catch (IOException e) {
            e.printStackTrace();
            ok = false;
        } finally {
            // assegurar que o canal out e fechado
            if ( out != null ) {
                try {
                    out.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }

        return ok;
    }

    /**
     * Retorna a password de um utilizador
     * @param user User utilizador a verificar
     * @return String password ou null
     */
    final String userPassword(User user) {

        FileInputStream in = null;
        try {
            // verifica se o ficheiro existe
            if ( !userFile.exists() )
                return null;

            in = new FileInputStream(userFile);

            // le e escreve o conteudo do ficheiro num string builder
            int r;
            StringBuilder sb = new StringBuilder();
            while((r = in.read()) != -1)
                sb.append((char) r);

            // divide o ficheiro em utilizadores
            String[] allUsers = sb.toString().split("\n");
            for(String u : allUsers) {
                // divide o utilizador das passwords
                String[] data = u.split(":");
                // se for encontrado o utilizador, retorna a password
                if ( data[0].equals(user.getName()) )
                    return data[1];
            }

        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            // assegurar que o canal in e fechado
            if ( in != null ) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return null;
    }

    /**
     * Guarda uma mensagem na directoria das mensagems
     * @param m Message mensagem a ser guardada
     * @return boolean foi possivel guardar ou nao
     */
    synchronized final boolean storeMessage(Message m) {
    	boolean ok = true;
        FileOutputStream out = null;

        try {
            String from      = m.getFrom().getName();
            String to        = m.getTo().getName();
            long timestamp   = m.getTime().getTime();
            String fileName  = "[" + from + "][" + to + "]_" + timestamp;

            File messageFile = new File(DIR_MESSAGES + fileName);
            ok = messageFile.createNewFile();

            out = new FileOutputStream(messageFile, true);

            out.write(m.getMessage().getBytes());
            out.flush();
            out.close();

        } catch (IOException e) {
            e.printStackTrace();
            ok = false;
        } finally {
            // assegurar que o canal out e fechado
            if ( out != null ) {
                try {
                    out.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }

        return ok;
    }

    /**
     * Retorna todas as mensagens do utilizador ao contacto distino
     * @param from User utilizador
     * @param to Contact contacto distino
     * @return Message[] array com todas as mensagens
     */
	final Message[] getMessages(User from, Contact to) {
		
		FileInputStream in = null;
        try {

            // create file in directory
            File dirFile = new File(DIR_MESSAGES);
            // filter files
            final Contact finalTo = to;
            File[] messageFiles = dirFile.listFiles(new FilenameFilter() {
                @Override
                public boolean accept(File dir, String name) {
                    String subString;
                    if ( finalTo != null )
                        subString = "[" + from.getName() + "][" + finalTo.getName() + "]";
                    else
                        subString = "[" + from.getName() + "]";
                    return name.startsWith(subString);
                }
            });

            if ( messageFiles == null || messageFiles.length == 0 )
                return null;

            ArrayList<Message> tempList = new ArrayList<>();
            
            Contact currTo = ( to == null ? null : to );
            for( File m : messageFiles ) {
                in = new FileInputStream(m);

                StringBuilder sb = new StringBuilder();
                int content;
                while ((content = in.read()) != -1)
                    sb.append((char)content);

                long time = Long.parseLong(m.getName().split("_")[1]);
                Timestamp timestamp = new Timestamp(time);

                if ( to == null ) {
                    String fileName = m.getName();
                    Pattern p = Pattern.compile("\\]\\[(.*)\\]_");
                    Matcher matcher = p.matcher(fileName);
                    if ( matcher.find() ) {
                        StringBuilder sbName = new StringBuilder(matcher.group(0));
                        sbName.delete(0,2);
                        sbName.delete(sbName.length()-2, sbName.length());
                        currTo = new User(sbName.toString());
                    }
                }

                Message msg = new Message(from, currTo, sb.toString(), timestamp);
                tempList.add(msg);
            }

            // transform to array
            Message[] array = new Message[tempList.size()];
            tempList.toArray(array);
            return array;

        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            // assegurar que o canal in e fechado
            if ( in != null ) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return null;
	}

    /**
     * Retorna todas as mensagens trocadas com o grupo
     * @param from Group group
     * @param to User contacto distino
     * @return Message[] array com todas as mensagens
     */
	final Message[] getMessagesFromGroup(Group from, User to) {
		FileInputStream in = null;
        try {

            // create file in directory
            File dirFile = new File(DIR_MESSAGES);
            // filter files
            File[] messageFiles = fileNameMatchesRegex("\\[(.*)\\]\\[" + from.getName() + "\\](.*)", dirFile);
            
            ArrayList<Message> tempList = new ArrayList<>();
            
            for( File f : messageFiles ) {
            	in = new FileInputStream(f);
        
                StringBuilder sb = new StringBuilder();
                int content;
                while ((content = in.read()) != -1)
                    sb.append((char)content);

                long time = Long.parseLong(f.getName().split("_")[1]);
                Timestamp timestamp = new Timestamp(time);
                
                String fileName = f.getName();
                Pattern p = Pattern.compile("\\[(.*)\\]\\[");
                Matcher matcher = p.matcher(fileName);
                User currFrom = null;
                if ( matcher.find() ) {
                    StringBuilder sbName = new StringBuilder(matcher.group(0));
                    sbName.delete(0,1);
                    sbName.delete(sbName.length()-2, sbName.length());
                    
                    if ( sbName.toString().equals(to.getName()) )
                    	continue;
                    currFrom = new User(sbName.toString());
                }

                Message msg = new Message(currFrom, to, sb.toString(), timestamp);
                tempList.add(msg);
            }
            
            // transform to array
            Message[] array = new Message[tempList.size()];
            tempList.toArray(array);
            return array;

        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            // assegurar que o canal in e fechado
            if ( in != null ) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return null;
	}

	/**
	 * Guarda um dado utilizador num dado grupo ou cria um novo
	 * @param group Group grupo a ser criado/inserido em
	 * @param user User utilizador a ser inserido
	 * @return boolean se foi possivel inserir ou nao
	 */
	synchronized final boolean addToGroup(Group group, User user) {
		boolean ok = true;
		FileOutputStream out = null;

		try {

            String groupName = group.getName();
            File groupFile = new File(DIR_GROUPS + groupName + ".txt");

            if ( !group.exists() )
                ok = groupFile.createNewFile();

			out = new FileOutputStream(groupFile, true);

            out.write(user.getName().getBytes());
            out.write("\n".getBytes());
            out.flush();
            out.close();

		} catch (IOException e) {
			e.printStackTrace();
			ok = false;
		} finally {
			// assegurar que o canal out e fechado
			if ( out != null ) {
				try {
					out.close();
				} catch (IOException e) {
					e.printStackTrace();
				}
			}
		}

		return ok;
	}

    /**
     * Retorna o dono de um dado grupo
     * @param group Group grupo a verificar
     * @return String nome do utilizador dono
     */
    final String groupOwner(Group group) {
        FileInputStream in = null;
        try {

            String groupName = group.getName();
            File groupFile = new File(DIR_GROUPS + groupName + ".txt");

            if ( !groupFile.exists() )
                return null;

            in = new FileInputStream(groupFile);

            StringBuilder sb = new StringBuilder();
            int content;
            while ((content = in.read()) != -1)
                sb.append((char)content);

            return sb.toString().split("\\n")[0];

        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            // assegurar que o canal in e fechado
            if ( in != null ) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return null;
    }

    /**
     * Verifica se um utilizador existe num grupo
     * @param group Group grupo a verificar
     * @param user User utilizador a verificar
     * @return boolean existe ou nao
     */
    final boolean groupUserExists(Group group, User user) {
        FileInputStream in = null;
        try {

            String groupName = group.getName();
            File groupFile = new File(DIR_GROUPS + groupName + ".txt");

            if ( !groupFile.exists() )
                return false;

            in = new FileInputStream(groupFile);

            StringBuilder sb = new StringBuilder();
            int content;
            while ((content = in.read()) != -1)
                sb.append((char)content);

            String[] allUsers = sb.toString().split("\\n");
            for( String u : allUsers ) {
                if (u.equals(user.getName()))
                    return true;
            }

        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            // assegurar que o canal in e fechado
            if (in != null) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return false;
    }

    /**
     * Remove todos os ficheiros associados a um grupo
     * @param group Group grupo a ser removido
     */
    synchronized final void removeGroup(Group group) {
        String groupName = group.getName();
        File groupFile = new File(DIR_GROUPS + groupName + ".txt");

        if ( !groupFile.exists() )
            return;

        // remove todas as mensagens e ficheiros de um grupo
        File messagesDir = new File(DIR_MESSAGES);
        File[] messageFiles = fileNameMatchesRegex("^\\[(.*)\\]\\[" + groupName + "\\]_(.*)", messagesDir);
        File uploadsDir = new File(DIR_UPLOADS);
        File[] uploadFiles = fileNameMatchesRegex("^\\[(.*)\\]\\[" + groupName + "\\](.*)", uploadsDir);
        
        ArrayList<File> allFiles = new ArrayList<>();
        allFiles.addAll(Arrays.asList(messageFiles));
        allFiles.addAll(Arrays.asList(uploadFiles));
        for( File f : allFiles )
            f.delete();

        // remove group file
        groupFile.delete();
    }

    /**
     * Retorna todos os ficheiros ao qual o nome e aceite por uma dada expressao regular
     * @param regex String expressao regular
     * @param directory File directoria dos ficheiros
     * @return File[] todos os ficheiros
     */
    private File[] fileNameMatchesRegex(String regex, File directory) {
        return directory.listFiles( new FilenameFilter() {
            @Override
            public boolean accept(File dir, String name) {
                Pattern p = Pattern.compile(regex);
                Matcher m = p.matcher(name);
                return m.find();
            }
        });
    }

    /**
     * Remove um utilizador de um dado grupo
     * @param group Group grupo a verificar
     * @param user User utilizador a ser removido
     * @return boolean se foi possivel remover ou nao
     */
    synchronized final boolean removeFromGroup(Group group, User user) {
        boolean ok = false;
        FileInputStream in = null;
        FileOutputStream out = null;
        try {

            String groupName = group.getName();
            File groupFile = new File(DIR_GROUPS + groupName + ".txt");

            if ( !groupFile.exists() )
                return false;

            in = new FileInputStream(groupFile);

            StringBuilder sb = new StringBuilder();
            int content;
            while ((content = in.read()) != -1)
                sb.append((char)content);

            // encontra o utilizador e alterar o seu conteudo para 'DELETED'
            String[] allUsers = sb.toString().split("\\n");
            for( int i = 0; i < allUsers.length; i++ ) {
                if ( allUsers[i].equals(user.getName())) {
                    allUsers[i] = "DELETED";
                    ok = true;
                }
            }

            // se nao encontrar nenhum utilizador
            if ( !ok )
                return false;

            // prevenir que o thread continue a executar sobre o ficheiro
            in.close();

            // fazer uma copia para outro ficheiro
            File oldFile = new File(DIR_GROUPS + groupName.toUpperCase() + "_OLD.txt");
            Files.move(groupFile.toPath(), oldFile.toPath());


            // criar o ficheiro novo sem o utilizador
            File newFile = new File(DIR_GROUPS + groupName + ".txt");
            ok = newFile.createNewFile();

            out = new FileOutputStream(newFile, true);

            // processar a nova string a ser escrita no ficheiro
            for ( String o : allUsers ) {
                if ( !o.equals("DELETED") ) {
                    out.write(o.getBytes());
                    out.write("\n".getBytes());
                }
            }

            // finalmente remover ficheiro antigo
            ok = oldFile.delete();

            out.flush();
            out.close();

            return ok;

        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            // assegurar que o canal in e fechado
            if (in != null) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            // assegurar que o canal out e fechado
            if (out != null) {
                try {
                    out.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
        return false;
    }

    /**
     * Guarda e obtem um ficheiro enviado por um utilizador
     * @param fileToUpload UserFile ficheiro a ser criado
     * @param rawBytes byte[] conjunto de bytes recebidos do cliente
     * @return long quantos bytes faltam receber
     */
    synchronized final long uploadUserFile(UserFile fileToUpload, byte[] rawBytes) {

        FileOutputStream out = null;
        try {

            String fileFullName = fileToUpload.getName();
            String[] fileParsedName = fileFullName.split("\\.", 2);
            String fileName = fileParsedName[0];
            String extension = fileParsedName[1];
            String from = fileToUpload.getFrom().getName();
            String to = fileToUpload.getTo().getName();

            String uploadName = "[" + from + "][" + to + "]_[" + fileName + "][" + extension + "]";
            File uploadFile = new File(DIR_UPLOADS + uploadName + "_-_-TEMP");
            if ( !uploadFile.exists() )
                uploadFile.createNewFile();

            out = new FileOutputStream(uploadFile, true);
            out.write(rawBytes);
            out.flush();
            out.close();

            long remainingBytes = fileToUpload.getLength() - uploadFile.length();
            if ( remainingBytes == 0L ) {
                long timestamp = new Date().getTime();
                uploadName += "_" + timestamp;

                // guarda a mensagem
                Message m = new Message(fileToUpload.getFrom(), fileToUpload.getTo(),
                        fileFullName, new Timestamp(timestamp) );
                ServerFilesManager.INSTANCE.storeMessage(m);

                // criar o ficheiro final
                File finishedFile = new File(DIR_UPLOADS + uploadName);
                Files.move(uploadFile.toPath(), finishedFile.toPath());
                System.out.println(" -> Ficheiro: [" + fileFullName + "] adicionado ao servidor.");
            }
            return remainingBytes;
        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            try {
                if (out != null) {
                    out.close();
                }
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        return 0L;
    }

    /**
     * Retorna o ficheiro mais recente inserido por um contacto
     * @param from Contact contacto que enviou o ficheiro
     * @param to User contacto distino
     * @param fileName String nome do ficheiro
     * @return File ficheiro ou null
     */
    final File getRecentFileFromTo(Contact from, User to, String fileName) {
        Message[] messages = getMessages(to, from);
        Message[] extraMessages = null;
        if ( from instanceof User )
            extraMessages = getMessages((User)from, to);
        else if ( from instanceof Group )
            extraMessages = getMessagesFromGroup((Group)from, to);

        ArrayList<Message> allMessages = new ArrayList<>();
        if ( messages != null )
        	allMessages.addAll(Arrays.asList(messages));
        if ( extraMessages != null )
        allMessages.addAll(Arrays.asList(extraMessages));
        	Collections.sort(allMessages);
        Collections.reverse(allMessages);
        
        long timestamp = 0L;
        for( Message m : allMessages ) {
            if ( m.getMessage().equals(fileName) ) {
                timestamp = m.getTime().getTime();
                break;
            }
        }
        final long fileTimestamp = timestamp;
        File[] matchedFiles = new File(DIR_UPLOADS).listFiles(new FilenameFilter() {
            @Override
            public boolean accept(File dir, String name) {
                return name.endsWith("_" + fileTimestamp);
            }
        });
        
        return ( matchedFiles.length > 0 ? matchedFiles[0] : null );
    }
}