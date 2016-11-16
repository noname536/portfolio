package sc.grupo3.fcul;

import java.sql.Timestamp;
import java.util.Date;

/**
 * Representa uma mensagem enviada entre contactos
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
final class Message implements Comparable<Message> {

	private User from;
	private Contact to;
	private String message;
	private Timestamp time;

	/**
	 * Construtor
	 * @param from User utilizador que originou a mensagem
	 * @param to Contact contacto distino
	 * @param message String conteudo da mensagem
	 */
	Message(User from, Contact to, String message) {
		this.from    = from;
		this.to      = to;
		this.message = message;
		this.time    = null;
	}

	/**
	 * Construtor
	 * @param from User utilizador que originou a mensagem
	 * @param to Contact contacto distino
	 * @param message String conteudo da mensagem
	 * @param time Timestamp timestamp da mensagem
	 */
	Message(User from, Contact to, String message, Timestamp time) {
		this.from    = from;
		this.to      = to;
		this.message = message;
		this.time    = time;
	}

	/**
	 * Getter do atributo from
	 * @return User utilizador que originou a mensagem
	 */
	final User getFrom() {
		return from;
	}

	/**
	 * Getter do atributo to
	 * @return Contact contacto distino
	 */
	final Contact getTo() {
		return to;
	}

	/**
	 * Getter do atributo message
	 * @return String conteudo da mensagem
	 */
	final String getMessage() {
		return message;
	}

	/**
	 * Getter do atributo time
	 * @return Timestamp timestamp
	 */
	final Timestamp getTime() {
		return time;
	}

	/**
	 * Envia esta mensagem
	 * @return boolean se foi possivel enviar ou nao
	 */
	final boolean send() {
		if ( to.exists() ) {
			
			if ( to instanceof Group ) {
				if ( ((Group)to).userExists(from) ) {
					this.time = new Timestamp(new Date().getTime());
					return ServerFilesManager.INSTANCE.storeMessage(this);
				}
			}
			else {
				this.time = new Timestamp(new Date().getTime());
				return ServerFilesManager.INSTANCE.storeMessage(this);
			}
		}
		return false;
	}

	/**
	 * Retorna todas as mensagens do utilizador ao contacto distino
	 * @param from User utilizador
	 * @param to Contact contacto distino
	 * @return Message[] array com todas as mensagems
	 */
	static Message[] findMessages(User from, Contact to) {
		if ( !from.exists() || !to.exists() )
			return null;

		return ServerFilesManager.INSTANCE.getMessages(from, to);
	}

	/**
	 * Compara duas mensagens
	 */
	@Override
	final public int compareTo(Message m) {
		if ( m.getTime().getTime() > this.time.getTime() )
			return -1;
		else if ( m.getTime().getTime() < this.time.getTime() )
			return 1;
		return 0;
	}


}
