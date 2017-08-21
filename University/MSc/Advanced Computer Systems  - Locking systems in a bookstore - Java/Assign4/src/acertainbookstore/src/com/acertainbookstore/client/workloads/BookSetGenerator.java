package com.acertainbookstore.client.workloads;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Random;
import java.util.Set;

import com.acertainbookstore.business.ImmutableStockBook;
import com.acertainbookstore.business.StockBook;

/**
 * Helper class to generate stockbooks and isbns modelled similar to Random
 * class
 */
public class BookSetGenerator {
	
	Random r;

	public BookSetGenerator() {
		r= new Random();
	}

	/**
	 * Returns num randomly selected isbns from the input set
	 * 
	 * @param num
	 * @return
	 */
	public Set<Integer> sampleFromSetOfISBNs(Set<Integer> isbns, int num) {
		HashSet <Integer> sample = new HashSet <Integer>();
		ArrayList <Integer> al = new ArrayList <Integer> ();
		al.addAll(isbns);
		Collections.shuffle(al);
		int i =0;
		while(sample.size() < num){
			sample.add(al.get(i));
			i++;
		}
		
		return sample;
	}

	/**
	 * Return num stock books. For now return an ImmutableStockBook
	 * 
	 * @param num
	 * @return
	 */
	public Set<StockBook> nextSetOfStockBooks(int num) {
		
		HashSet <StockBook> sample = new HashSet <StockBook> ();
		StringBuilder sb = new StringBuilder();
		String nameTitle= "Lord of the Rings ";
		String author = " J. R. R. Tolkien ";
		sb.append(nameTitle);
		while(sample.size() < num){
			int isbnR = r.nextInt(999999999);
			int numCopies = r.nextInt(999);
			float priceR = (float) (r.nextFloat() * (1000-0.50)-0.50);
			sb.append(r.nextInt(99999));
			String finalTitle = sb.toString();
			sb.setLength(0);
			sb.append(author);
			sb.append(r.nextInt(99999));
			String finalAuthor = sb.toString();
			long numSaleMisses = r.nextLong();
			long numTimesRated = r.nextLong();
			long totalRating = r.nextLong();
			boolean editorPick = r.nextBoolean();
			ImmutableStockBook stockBook = new ImmutableStockBook (isbnR,finalTitle,finalAuthor,priceR,numCopies,numSaleMisses,numTimesRated,totalRating,editorPick);
			sample.add(stockBook);
		}
		
		return sample;
	}

}
