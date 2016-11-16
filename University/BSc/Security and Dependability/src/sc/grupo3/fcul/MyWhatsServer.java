package sc.grupo3.fcul;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;

/**
 * Classe servidor da aplicacao MyWhats
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
public class MyWhatsServer {

	/**
	 * Metodo main
	 * @param args
	 */
	public static void main(String[] args) {
		
		// tratamento de erros
		if ( args.length < 1 ) {
			System.out.println("Sintaxe incorreta: MyWhatsServer <porto>");
			return;
		}

		// obter o porto
		int port = Integer.parseInt(args[0]);

		// execucao principal do servidor
		try {
			MyWhatsServer.startServer(port);
		} catch(IOException e){
			e.printStackTrace();
		}

	}

	/**
	 * Inicia o servidor num dado porto
	 * @param port int porto do servidor
	 * @throws IOException 
	 */
	private static void startServer(int port) throws IOException {
		ServerSocket socket = new ServerSocket(port);

		System.out.println("*** Servidor com o porto " + port + " inicializado.");
		System.out.println("*** Servidor a espera de clientes...");
		// espera por varios clientes
		while(true) {
			Socket clientSocket = socket.accept();

			// criacao de uma thread para executar aquele cliente
			ServerThread thread = new ServerThread(clientSocket); 
			(new Thread(thread)).start();
		}
	}
}
