package sc.grupo3.fcul;

/**
 * Representa um possivel contacto em que se pode trocar
 * mensagens atraves do sistema
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
abstract class Contact {

	private String name;

	/**
	 * Construtor
	 * @param name String nome do contacto
     */
	Contact(String name) {
		this.name = name;
	}

	/**
	 * Retorna o nome deste contacto
	 * @return String nome do contacto
     */
	final String getName() {
		return name;
	}

	/**
	 * Indica de o contacto existe no sistema ou nao
	 * @return boolean se o contacto existe
     */
	abstract boolean exists();

	/**
	 * Regista o contacto no sistema associado a uma chave
	 * @param key objecto a associar
	 * @return boolean se foi possivel registar ou nao
     */
	abstract boolean register(Object key);

}
