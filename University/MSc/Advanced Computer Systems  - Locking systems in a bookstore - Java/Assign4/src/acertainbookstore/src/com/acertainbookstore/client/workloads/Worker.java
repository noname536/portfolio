/**
 * 
 */
package com.acertainbookstore.client.workloads;

import java.util.Collections;
import java.util.Comparator;
import java.util.HashSet;
import java.util.Iterator;
import java.util.List;
import java.util.Random;
import java.util.Set;
import java.util.concurrent.Callable;

import com.acertainbookstore.business.Book;
import com.acertainbookstore.business.BookCopy;
import com.acertainbookstore.business.CertainBookStore;
import com.acertainbookstore.business.StockBook;
import com.acertainbookstore.interfaces.StockManager;
import com.acertainbookstore.utils.BookStoreException;

/**
 * 
 * Worker represents the workload runner which runs the workloads with
 * parameters using WorkloadConfiguration and then reports the results
 * 
 */
public class Worker implements Callable<WorkerRunResult> {
	private WorkloadConfiguration configuration = null;
	private int numSuccessfulFrequentBookStoreInteraction = 0;
	private int numTotalFrequentBookStoreInteraction = 0;

	public Worker(WorkloadConfiguration config) {
		configuration = config;
	}

	/**
	 * Run the appropriate interaction while trying to maintain the configured
	 * distributions
	 * 
	 * Updates the counts of total runs and successful runs for customer
	 * interaction
	 * 
	 * @param chooseInteraction
	 * @return
	 */
	private boolean runInteraction(float chooseInteraction) {
		try {
			float percentRareStockManagerInteraction = configuration.getPercentRareStockManagerInteraction();
			float percentFrequentStockManagerInteraction = configuration.getPercentFrequentStockManagerInteraction();

			if (chooseInteraction < percentRareStockManagerInteraction) {
				runRareStockManagerInteraction();
			} else if (chooseInteraction < percentRareStockManagerInteraction
					+ percentFrequentStockManagerInteraction) {
				runFrequentStockManagerInteraction();
			} else {
				numTotalFrequentBookStoreInteraction++;
				runFrequentBookStoreInteraction();
				numSuccessfulFrequentBookStoreInteraction++;
			}
		} catch (BookStoreException ex) {
			return false;
		}
		return true;
	}

	/**
	 * Run the workloads trying to respect the distributions of the interactions
	 * and return result in the end
	 */
	public WorkerRunResult call() throws Exception {
		int count = 1;
		long startTimeInNanoSecs = 0;
		long endTimeInNanoSecs = 0;
		int successfulInteractions = 0;
		long timeForRunsInNanoSecs = 0;

		Random rand = new Random();
		float chooseInteraction;

		// Perform the warmup runs
		while (count++ <= configuration.getWarmUpRuns()) {
			chooseInteraction = rand.nextFloat() * 100f;
			runInteraction(chooseInteraction);
		}

		count = 1;
		numTotalFrequentBookStoreInteraction = 0;
		numSuccessfulFrequentBookStoreInteraction = 0;

		// Perform the actual runs
		startTimeInNanoSecs = System.nanoTime();
		while (count++ <= configuration.getNumActualRuns()) {
			chooseInteraction = rand.nextFloat() * 100f;
			if (runInteraction(chooseInteraction)) {
				successfulInteractions++;
			}
		}
		endTimeInNanoSecs = System.nanoTime();
		timeForRunsInNanoSecs += (endTimeInNanoSecs - startTimeInNanoSecs);
		return new WorkerRunResult(successfulInteractions, timeForRunsInNanoSecs, configuration.getNumActualRuns(),
				numSuccessfulFrequentBookStoreInteraction, numTotalFrequentBookStoreInteraction);
	}

	/**
	 * Runs the new stock acquisition interaction
	 * 
	 * @throws BookStoreException
	 */
	private void runRareStockManagerInteraction() throws BookStoreException {
		BookSetGenerator bsg = configuration.getBookSetGenerator();
		Set<StockBook> set = bsg.nextSetOfStockBooks(10);
		List<StockBook> al = configuration.getStockManager().getBooks();
		HashSet<StockBook> hSet = new HashSet<StockBook>();
		if (!al.containsAll(set)) {
			Iterator<StockBook> it = set.iterator();
			while (it.hasNext()) {
				StockBook sb = it.next();
				if (al.contains(sb)) {
					hSet.add(sb);
				}
			}
		}
		if (hSet.size() > 0)
			configuration.getStockManager().addBooks(hSet);
	}

	/**
	 * Runs the stock replenishment interaction
	 * 
	 * @throws BookStoreException
	 */
	private void runFrequentStockManagerInteraction() throws BookStoreException {
		List<StockBook> al = configuration.getStockManager().getBooks();
		int k = 5;
		Collections.sort(al, new Comparator<StockBook>() {
			@Override
			public int compare(StockBook arg0, StockBook arg1) {
				if(arg0.getNumCopies() < arg1.getNumCopies()){
					return 1;
				}else if(arg0.getNumCopies()> arg1.getNumCopies()){
					return -1;
				}else{
					return 0;
				}
			}
		});
		Set<BookCopy> setF = new HashSet<BookCopy>();
		for(int i = 0; i <k; i ++){
			setF.add(new BookCopy(al.get(i).getISBN(), 2));
		}
		configuration.getStockManager().addCopies(setF);
	}

	/**
	 * Runs the customer interaction
	 * 
	 * @throws BookStoreException
	 */
	private void runFrequentBookStoreInteraction() throws BookStoreException {
		BookSetGenerator bsg = configuration.getBookSetGenerator();
		List<Book> al = configuration.getBookStore().getEditorPicks(10);
		Set<Integer> setAl = new HashSet<Integer>();
		for (int i = 0; i < al.size(); i++) {
			setAl.add(al.get(i).getISBN());
		}
		if (setAl.size() > 0){ //in case 0 editor picks
		Set<Integer> finalSet = bsg.sampleFromSetOfISBNs(setAl, 3);
		Set<BookCopy> setF = new HashSet<BookCopy>();

		Iterator<Integer> it = finalSet.iterator();
		while (it.hasNext()) {
			setF.add(new BookCopy(it.next(), 1));
		}
		configuration.getBookStore().buyBooks(setF);
		}
	}

}
