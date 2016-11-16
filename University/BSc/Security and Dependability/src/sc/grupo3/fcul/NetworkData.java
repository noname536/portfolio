package sc.grupo3.fcul;

import java.io.*;
import java.util.HashMap;
import java.util.Map;

/**
 * Representacao de um conjunto de dados serializados que irao ser enviados pela rede
 *
 * @author Fernando Alves, 45592
 * @author Jose Franco,    44914
 * @author Nuno Ribeiro,   39280
 */
public final class NetworkData implements Serializable {

	/**
	 * ID unico da classe
	 */
	private static final long serialVersionUID = -4087647487743602384L;

	private String userName;
    private Map<String, String> data;

    /**
     * Construtor
     * @param userName String nome do utilizador que enviou este pacote
     */
    public NetworkData(String userName) {
        this.userName = userName;
        this.data = new HashMap<>();
    }

    /**
     * Getter do atributo userName
     * @return String nome do utilizador que enviou este pacote
     */
    public final String getUserName() {
        return userName;
    }

    /**
     * Insere um valor no conjunto de dados
     * @param key String chave
     * @param value String valor
     */
    public final void put(String key, String value) {
        data.put(key, value);
    }

    /**
     * Remove o valor associado a uma chave do conjunto de dados
     * @param key String chave
     */
    public final void remove(String key) {
        data.remove(key);
    }

    /**
     * Retorna o valor associado a uma chave
     * @param key String chave
     * @return String valor
     */
    public final String get(String key) {
        return data.get(key);
    }
    
    /**
     * Retorna todas as chaves existentes no conjunto de dados
     * @return String[] todas as chaves
     */
    public final String[] getAllKeys() {
    	Object[] rawKeys = data.keySet().toArray();
    	String[] keys = new String[rawKeys.length];
    	
    	for( int i = 0; i < rawKeys.length; i++ )
    		keys[i] = (String) rawKeys[i];
    	
    	return keys;
    }
    
    /**
     * Verifica se uma chave pertence ao conjunto de dados
     * @param key String chave
     * @return boolean se existe ou nao
     */
    public final boolean containsKey(String key) {
    	return data.containsKey(key);
    }
    
    /**
     * Copia os valores de uma outra NetworkData para esta
     * @param other NetworkData o outro conjunto de dados
     */
    public final void copyValues(NetworkData other) {
    	// ler todas as chaves da outra network data
    	for(String s : other.getAllKeys())
    		data.put(s, other.get(s) );
    }

    /**
     * Representacao textual do conjunto de dados
     * @return String representacao textual
     */
    @Override
    public final String toString() {
        return "NetworkData: {" +
                "userName = '" + userName + "\'" +
                ", data = " + data.toString() +
                "}";
    }
}
