package sc.grupo3.fcul;

/**
 * Representa um grupo (conjunto de contactos)
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
final class Group extends Contact {

	/**
	 * Construtor
	 * @param name String nome do contacto
     */
	Group(String name) {
		super(name);
	}

	/**
	 * Retorna o nome deste contacto
	 * @return nome do contacto
	 */
	@Override
	boolean exists() {
		return ServerFilesManager.INSTANCE.groupOwner(this) != null;
	}


	/**
	 * Regista o contacto no sistema associado a uma chave
	 * @param key objecto a associar
	 * @return boolean se foi possivel registar ou nao
	 */
	@Override
	boolean register(Object key) {
		User user = (User) key;
		return !ServerFilesManager.INSTANCE.groupUserExists(this, user)
				&& ServerFilesManager.INSTANCE.addToGroup(this, user);
	}

	/**
	 * Retorna se um dado utilizador e o dono do grupo ou nao
	 * @param user User utilizador a verificar
	 * @return boolean se e dono ou nao
     */
	boolean isOwner(User user) {
		String groupOwner = ServerFilesManager.INSTANCE.groupOwner(this);
		return groupOwner != null && groupOwner.equals(user.getName());
	}

	/**
	 * Remove um utilizador do grupo
	 * @param user User utilizador a ser removido
	 * @return boolean se foi possivel remover ou nao
     */
	boolean remove(User user) {
		return ServerFilesManager.INSTANCE.removeFromGroup(this, user);
	}

	/**
	 * Remove o grupo inteiro
	 */
	void clear() {
		ServerFilesManager.INSTANCE.removeGroup(this);
	}

	/**
	 * Verifica se um utilizador existe no grupo ou nao
	 * @param user User utilizador a verificar
	 * @return boolean se existe ou nao
     */
	boolean userExists(User user) {
		return ServerFilesManager.INSTANCE.groupUserExists(this, user);
	}

}
