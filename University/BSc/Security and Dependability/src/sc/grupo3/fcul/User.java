package sc.grupo3.fcul;

/**
 * Representacao de um utilizador unico do sistema
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
final class User extends Contact {

	
	/**
	 * Construtor
	 * @param name String nome do utilizador
	 */
	User(String name) {
		super(name);
	}

	/**
	 * Retorna o nome deste contacto
	 * @return nome do contacto
	 */
	@Override
	final boolean exists() {
		return ServerFilesManager.INSTANCE.userPassword(this) != null;
	}


	/**
	 * Regista o contacto no sistema associado a uma chave
	 * @param key objecto a associar
	 * @return boolean se foi possivel registar ou nao
	 */
	@Override
	final boolean register(Object key) {
		String password = (String) key;
		return ServerFilesManager.INSTANCE.registerUser(this, password);
	}

	/**
	 * Autentica o utilizador
	 * @param password String password a verificar
	 * @return boolean foi possivel autenticar ou nao
	 */
	final boolean login(String password) {
		String registeredPassword = ServerFilesManager.INSTANCE.userPassword(this);
		return password.equals(registeredPassword);
	}

}
