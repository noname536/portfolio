package sc.grupo3.fcul;

/**
 * Representacao de um ficheiro enviado por utilizador
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
class UserFile {

    private User from;
    private Contact to;
    private String name;
    private long length;

    /**
     * Construtor
     * @param from User utilizador que enviou este ficheiro
     * @param to Contact contacto distino
     * @param name String nome do ficheiro
     * @param length long tamanho total de bytes do ficheiro
     */
    UserFile(User from, Contact to, String name, long length) {
        this.from = from;
        this.to = to;
        this.name = name;
        this.length = length;
    }

    /**
     * Getter do atributo from
     * @return User utilizador que enviou este ficheiro
     */
    User getFrom() {
        return from;
    }

    /**
     * Getter do atributo to
     * @return Contact contacto distino
     */
    Contact getTo() {
        return to;
    }

    /**
     * Getter do atributo name
     * @return String nome do ficheiro
     */
    String getName() {
        return name;
    }

    /**
     * Getter do atributo length
     * @return long tamanho total de bytes do ficheiro
     */
    long getLength() {
        return length;
    }
}
