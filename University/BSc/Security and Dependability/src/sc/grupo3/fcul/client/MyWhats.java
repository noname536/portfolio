package sc.grupo3.fcul.client;

import java.io.*;
import java.net.Socket;
import java.util.Arrays;
import java.util.Scanner;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import sc.grupo3.fcul.NetworkData;

/**
 * Classe cliente da aplicacao MyWhats
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
public final class MyWhats {

	private static final String DIR_CLIENT = "client_downloads/";
	private static final int BUFFER_SIZE = 1024;
	private static File uploadFile;
	private static File downloadFile;
	private static String downloadFileName;
	private static long downloadFileSize = 0L;
	private static boolean downloadedFileComplete = true;

	/**
	 * Metodo main
	 * @param args
	 */
	public static void main(String[] args) {
		
		// tratar os erros
		if ( args.length < 2 ) {
			System.out.println("Sintaxe errada: myWhats <localUser> <serverAddress> [flags]");
			System.out.println("Flags:");
			System.out.println("-p <password>");
			System.out.println("-m <contact> <message>");
			System.out.println("-f <contact> <file>");
			System.out.println("-r contact file");
			System.out.println("-a <user> <group>");
			System.out.println("-d <user> <group>");
			return;
		}
		
		// indice das flags
		int flagIndex = 0;

		// utilizador
		String user = args[flagIndex];

		if ( user.contains(":") ) {
			System.out.println("Nome de utilizador invalido. Insira outro por favor.");
			return;
		}

		// inicializar estrutura de envio de dados
		NetworkData data = new NetworkData(user);

		// obter o endereco
		String[] rawIP = args[++flagIndex].split(":");
		// tratar os possiveis erros do endereco
		if ( rawIP.length < 1 ) {
			System.out.println("Endereco IP errado. Sintaxe correcta: <ip>:<port>");
			return;
		}

		String ip        = rawIP[0];
		int port         = Integer.parseInt(rawIP[1]);

		// tratar a password
		String userPassword = null;
		if ( args[++flagIndex].equals("-p")) {
			if ( args.length < flagIndex+1 )
				System.out.println("Sintaxe errada: -p <password>");
			else 
				userPassword = args[++flagIndex];
		}
		else {
			Scanner sc = new Scanner(System.in);
			System.out.print("Insira a password: ");
			userPassword = sc.nextLine();
			sc.close();
			flagIndex--;
		}
		data.put("-p", userPassword);
		
		// tratar as restantes flags
		
		switch(args[++flagIndex]) {
			case "-m":
				// falta argumentos
				if ( args.length < flagIndex + 3 )
					System.out.println("Sintaxe errada: -m <contact> <message>");
				else {
					StringBuilder rawMessage = new StringBuilder();
	
					// buscar o contacto
					String contact = args[++flagIndex];
					rawMessage.append(contact).append(":");
	
					// buscar a mensagem de tamanho dinamico
					String text = "";
					int indexesLeft = args.length - flagIndex;
					for( int i = flagIndex+1; i < args.length; i++ )
						text += args[i] + " ";
					rawMessage.append(text);
	
					data.put("-m", rawMessage.toString());
	
				}
				break;
			case "-f":
				// falta argumentos
				if ( args.length < flagIndex + 3 )
					System.out.println("Sintaxe errada: -f <contact> <file>");
				else {
					StringBuilder rawMessage = new StringBuilder();
					String contact = args[++flagIndex];
					String filePath = args[++flagIndex];
	
					uploadFile = new File(filePath);
					if ( !uploadFile.exists() ) {
						System.out.println("Ficheiro [" + filePath + "] nao existe.");
						return;
					}
					else {
						long fileSize = uploadFile.length();
						rawMessage.append(contact).append(":")
							.append(filePath).append(":")
							.append(fileSize);
						data.put("-f", rawMessage.toString());
					}
				}
				break;
			case "-r":
				// falta argumentos
				if ( flagIndex == (args.length-1) ) {
					data.put("-r", "");
				}
				else if ( flagIndex + 1 == (args.length-1) ) {
					data.put("-r_1", args[++flagIndex]);
				}
				else if ( flagIndex + 2 == (args.length-1) ) {
					String contact  = args[++flagIndex];
					downloadFileName = args[++flagIndex];

					StringBuilder rawMessage = new StringBuilder();
					rawMessage.append(contact).append(":")
							.append(downloadFileName);

					data.put("-r_2", rawMessage.toString());
				}
				break;
			case "-a":
				// falta argumentos
				if ( args.length < flagIndex + 3 )
					System.out.println("Sintaxe errada: -a <user> <group>");
				else {
					StringBuilder rawMessage = new StringBuilder();
					rawMessage.append(args[++flagIndex]).append(":")
							.append(args[++flagIndex]);
	
					data.put("-a", rawMessage.toString());
				}
				break;
			case "-d":
				// falta argumentos
				if ( args.length < flagIndex + 3 )
					System.out.println("Sintaxe errada: -d <user> <group>");
				else {
					StringBuilder rawMessage = new StringBuilder();
					rawMessage.append(args[++flagIndex]).append(":").append(args[++flagIndex]);
	
					data.put("-d", rawMessage.toString());
				}
				break;
			default:
				break;
		}
		
		// conexao do socket
		try {
			MyWhats.startClient(ip, port, data);
		} catch (IOException e) {
			System.out.println("Nao foi possivel realizar a conexao no ip: " + ip + ":" + port + "..." );
		}
	}

	/**
	 * Metodo que inicia a conexao do cliente ao servidor e troca dados entre eles
	 * @param ip String ipv4 ou nome de dominio do servidor
	 * @param port int port do servidor
	 * @param send NetworkData conjunto de dados inicial a enviar ao servidor
	 * @throws IOException
     */
	private static void startClient(String ip, int port, NetworkData send) throws IOException {
		Socket socket = new Socket(ip, port);

		ObjectInputStream  in  = null;
		ObjectOutputStream out = null;

		File clientDir = new File(DIR_CLIENT);
		clientDir.mkdirs();
		
		try {
			// ----------------------------------------------------------------
			out = new ObjectOutputStream(socket.getOutputStream());
			in  = new ObjectInputStream(socket.getInputStream());
			// ----------------------------------------------------------------
			
			// ----------------------------------------------------------------
			// enviar dados ao servidor
			//
			out.writeObject(send);
			
			// ----------------------------------------------------------------
			// receber dados do servidor
			//
			NetworkData received = (NetworkData) in.readObject();
			
			// ----------------------------------------------------------------
			// enviar possiveis dados ao servidor
			NetworkData resend = serverDataInterpreter(send.getUserName(), received, in, out );
			
			// download dos dados do ficheiro 
			// caso o servidor esteja a enviar
			if ( downloadFileSize > 0L )
				downloadFileData(in);

			while ( resend != null ) {
				
				// ------------------------------------------------------------
				// enviar dados ao servidor
				//
				out.writeObject(resend);
				
				// ------------------------------------------------------------
				// receber dados do servidor
				//
				NetworkData ack = (NetworkData) in.readObject();
				resend = serverDataInterpreter(send.getUserName(), ack, in, out);
			}
			
			

		} catch (IOException | ClassNotFoundException e) {
			e.printStackTrace();
		} finally {
			// ------------------------------------------------------------
			// fechar conexoes
			if ( out != null )
				out.close();
			if ( in != null )
				in.close();
		}

		socket.close();
	}

	/**
	 * Metodo que interpreta como o cliente deve responder dado uma resposta do servidor
	 * @param user String nome de utilizador deste cliente
	 * @param received NetworkData dados recebidos do servidor
	 * @param in ObjectInputStream stream de dados de input da socket
	 * @param out ObjectOutputStream stream de dados de output da socket
     * @return null ou um novo NetworkData a ser enviado
     */
	private static NetworkData serverDataInterpreter(String user, NetworkData received,
													 ObjectInputStream in, ObjectOutputStream out) {
		NetworkData send = null;
		
		// servidor requer uma password do cliente
		String request = received.get("request");
		if ( request != null ) {

			// servidor requer que o cliente envie o ficheiro
			if ( request.equals("send_file") ) {

				// remover o request
				received.remove("request");

				// ler o conteudo do ficheiro de BUFFER_SIZE a BUFFER_SIZE e enviar
				FileInputStream fileInput = null;
				try {
					fileInput = new FileInputStream(uploadFile);

					int bytes;
					int sentBytes = 0;
					int remaining = Math.min(BUFFER_SIZE, (int)(uploadFile.length() - sentBytes));
					byte[] chunk = new byte[remaining];
					while( (bytes = fileInput.read(chunk, 0, remaining)) > 0 ) {
						sentBytes += bytes;
						// enviar para o servidor
						out.write(chunk);
					}
					System.out.println("Ficheiro enviado ao servidor com sucesso.");
				} catch (IOException e) {
					e.printStackTrace();
				} finally {
					try {
						if ( fileInput != null ) {
							fileInput.close();
						}
					} catch (IOException e) {
						e.printStackTrace();
					}
				}

			}
			// servidor informa o cliente que esta prestes a enviar um ficheiro
			else if ( request.startsWith("receive_file") ) {

				String[] requestInfo = request.split(":", 2);

				// remover o request
				received.remove("request");

				if ( requestInfo[1].startsWith("error") ) {
					System.out.println(requestInfo[1].split(":")[1]);
				}
				else
				{
					downloadFileSize = Long.parseLong(requestInfo[1].split(":")[1]);
				}


			}
			// cliente recebe o ficheiro do servidor
			else if ( request.startsWith("download_file") ) {
				downloadFileData(in);
			}
		}

		// imprimir a mensagem de autenticacao
		if ( received.containsKey("-p") ) {
			System.out.println( received.get("-p") );
			received.remove("-p");
		}

		// imprimir os restantes acks do servidor
		String[] allAcks = received.getAllKeys();
		for( String s : allAcks )
			System.out.println( received.get(s) );

		
		return send;
	}

	/**
	 * Recebe os dados de um ficheiro do servidor
	 * @param in ObjectInputStream stream de input da socket
     */
	private static void downloadFileData(ObjectInputStream in) {
		System.out.println("A fazer download do ficheiro do servidor...");
		FileOutputStream fileOut = null;
		try {

			File downloadFile = new File(DIR_CLIENT + downloadFileName);
			if ( downloadedFileComplete ) {
				while( downloadFile.exists() ) {
					String name = downloadFile.getName();
					String[] fullName = name.split("\\.", 2);
					downloadFile = new File(DIR_CLIENT + fullName[0] + " (1)." + fullName[1] );
					
				}
				if ( !downloadFile.exists() )
					downloadFile.createNewFile();
			}
			
			fileOut = new FileOutputStream(downloadFile, true);

			int bytesRead = 0;
			int bytes = 0;
			boolean continueReceiving = true;
			downloadedFileComplete = false;

			while (continueReceiving) {
				int remaining = Math.min(BUFFER_SIZE, (int) (downloadFileSize - bytesRead));
				byte[] rawBytes = new byte[remaining];
				bytes = in.read(rawBytes, 0, remaining);
				if ( bytes <= 0 ) {
					continueReceiving = false;
					break;
				}
				bytesRead += bytes;
				fileOut.write(rawBytes);
				fileOut.flush();
				
				continueReceiving = (downloadFile.length() - downloadFileSize) != 0L;

			}
			if ( (downloadFile.length() - downloadFileSize) == 0L ) {
				System.out.println("Download realizado com sucesso.");
				downloadedFileComplete = true;
			}
		} catch (IOException e) {
			e.printStackTrace();
		} finally {
			try {
				if ( fileOut != null ) {
					fileOut.close();
				}
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
		downloadFileSize = 0L;
	}
}
