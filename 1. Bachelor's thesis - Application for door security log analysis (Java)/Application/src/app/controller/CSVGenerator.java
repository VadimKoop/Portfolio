package app.controller;

import app.model.LogEntry;
import javafx.scene.control.TableView;

import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.*;
import java.util.function.Function;

public class CSVGenerator {

    public static String getLogEntryCSV(TableView tblLog) {
        String csv = LogEntry.CSV_HEADER;
        for(Object item : tblLog.getItems()) {
            LogEntry e = (LogEntry) item;
            csv += "\n" + e.toCSVRow();
        }
        return csv;
    }

    public static String getDailyStatsCSV(TableView tblLog, Function<LogEntry, String> pivotBy) {

        Set<String> pivotSet = new HashSet<>();

        TreeMap<String, TreeMap<LocalDate, TreeMap<String, Integer>>> table = new TreeMap<>();

        for(Object item : tblLog.getItems()) {
            LogEntry e = (LogEntry) item;
            String pivot = pivotBy.apply(e);
            pivotSet.add(pivot);
            String eventType = e.getType();
            LocalDate date = e.getTimestamp().toLocalDate();
            if(!table.containsKey(eventType)) {
                table.put(eventType, new TreeMap<>());
            }
            TreeMap<LocalDate, TreeMap<String, Integer>> t = table.get(eventType);
            if(!t.containsKey(date)) {
                t.put(date, new TreeMap<>());
            }
            TreeMap<String, Integer> row = t.get(date);
            if(row.containsKey(pivot)) {
                row.put(pivot, row.get(pivot) + 1);
            } else {
                row.put(pivot, 1);
            }
        }

        List<String> pivots = new ArrayList<>();
        pivots.addAll(pivotSet);
        pivots.sort(String::compareTo);

        String csv = "Event,Timestamp";
        for(String pivot : pivots) {
            csv += "," + pivot;
        }
        csv += "\n";

        for(String eventType : table.keySet()) {
            TreeMap<LocalDate, TreeMap<String, Integer>> t = table.get(eventType);
            for(LocalDate date : t.keySet()) {
                TreeMap<String, Integer> row = t.get(date);
                csv += eventType + "," + date.format(DateTimeFormatter.ofPattern("YYYY-MM-dd"));
                for(String pivot: pivots) {
                    if(row.containsKey(pivot)) {
                        csv += "," + row.get(pivot);
                    } else {
                        csv += ",0";
                    }
                }
                csv += "\n";
            }
        }

        return csv;
    }

}
