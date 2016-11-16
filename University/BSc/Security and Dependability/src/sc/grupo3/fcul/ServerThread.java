package sc.grupo3.fcul;

import java.io.*;
import java.net.Socket;
import java.util.Arrays;

/**
 * Classe interna que representa um thread do servidor
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
final class ServerThread implements Runnable {

	private static final int BUFFER_SIZE = 1024;

	private Socket socket;
	private boolean waitingForFile;
	private long currentFileLength;
	private File downloadFile;
	private boolean logged;
	
	/**
	 * Construtor do thread do cliente
	 * @param socket
	 */
	ServerThread(Socket socket) {
		this.socket = socket;
		this.waitingForFile = false;
		this.downloadFile = null;
		this.logged = false;
	}
	
	/**
	 * Metodo de execucao do thread do cliente
	 */
	@Override
	public void run() {
		ObjectInputStream  in  = null;
		ObjectOutputStream out = null;
		
		try {
			// ----------------------------------------------------------------
			in  = new ObjectInputStream(socket.getInputStream());
			out = new ObjectOutputStream(socket.getOutputStream());
			// ----------------------------------------------------------------

			// ------------------------------------------------------------
			// obter dados do cliente
			//
			NetworkData received = (NetworkData) in.readObject();
			System.out.println(received.toString());

			// ------------------------------------------------------------
			// enviar resposta ao cliente
			//
			NetworkData send = clientDataInterpreter(received);
			System.out.println(send.toString());
			out.writeObject(send);

			// ------------------------------------------------------------
			// obter dados dos ficheiros do cliente
			//
			int bytesRead = 0;
			while (waitingForFile) {
				int remaining = Math.min(BUFFER_SIZE, (int) (currentFileLength - bytesRead));
				byte[] rawBytes = new byte[remaining];
				int bytes = in.read(rawBytes, 0, remaining);
				if ( bytes <= 0 ) {
					waitingForFile = false;
					break;
				}
				bytesRead += bytes;
				long bytesLeft = Task.writeFile(rawBytes);

				waitingForFile = (bytesLeft != 0L);
			}
			
			// ------------------------------------------------------------
			// enviar dados de um ficheiro ao cliente
			//
			FileInputStream fileInput = null;
			if ( downloadFile != null ) {
				try {
					fileInput = new FileInputStream(downloadFile);

					int bytes = 0;
					int sentBytes = 0;
					int remaining = Math.min(BUFFER_SIZE, (int)(downloadFile.length() - sentBytes));;
					byte[] chunk = new byte[remaining];

					while( (bytes = fileInput.read(chunk, 0, remaining)) > 0 ) {
						sentBytes += bytes;
						// enviar para o cliente
						out.write(chunk);
					}
					System.out.println("*** Ficheiro enviado ao cliente com sucesso.");
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
				downloadFile = null;
			}
			
			// ----------------------------------------------------------------
			// fechar sockets
			in.close();
			out.close();
			socket.close();

		} catch (IOException | ClassNotFoundException e) {
			e.printStackTrace();
		}

	}

	/**
	 * Metodo que interpreta os dados recebidos do cliente
	 * @param received NetworkData dados recebidos do cliente
     * @return NetworkData novo com as respostas
     */
	private NetworkData clientDataInterpreter(NetworkData received) {
		NetworkData send = new NetworkData("SERVER-" + Thread.currentThread().getName());

		User user = new User(received.getUserName());
		// realizar a autenticacao/registo
		if ( received.containsKey("-p") ) {

			String pass = received.get("-p");
			logged = Task.authenticate(user, pass, send);
		}
		
		// iterar todas as chaves inseridas
		if ( logged ) {
			String[] allKeys = received.getAllKeys();
			for( String s : allKeys ) {
				String rawData = received.get(s);
				String[] data = rawData.split(":");
				Group group;
				User target;
				Contact to;
				Contact from;
				switch(s) {
					case "-m":
						to = new User(data[0]);
						if ( !to.exists() )
							to = new Group(data[0]);
						Message message = new Message(user, to, data[1].trim());
						Task.sendMessage(message, send);
						break;
					case "-f":
						to = new User(data[0]);
						if ( !to.exists() )
							to = new Group(data[0]);
						String fileName = data[1];
						currentFileLength = Long.parseLong(data[2]);
						waitingForFile = Task.uploadFile(user, to, fileName, currentFileLength, send);
						break;
					case "-r":
						Task.getAllContactsRecentInfo(user, send);
						break;
					case "-r_1":
						to = new User(data[0]);
						if ( !to.exists() )
							to = new Group(data[0]);
						Task.getContactInfo(user, to, send);
						break;
					case "-r_2":
						from = new User(data[0]);
						if ( !from.exists() )
							from = new Group(data[0]);
						downloadFile = Task.downloadFile(user, from, data[1], send);
						break;
					case "-a":
						group = new Group(data[1]);
						target = new User(data[0]);
						Task.addToGroup(group, user, target, send);
						break;
					case "-d":
						group = new Group(data[1]);
						target = new User(data[0]);
						Task.removeFromGroup(group, user, target, send);
						break;
				}
			}
		}

		return send;
	}
	
}
