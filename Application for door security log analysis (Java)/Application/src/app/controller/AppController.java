package app.controller;

import app.model.DataModel;
import app.model.LogEntry;
import app.view.DateTimePicker;
import javafx.collections.FXCollections;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import javafx.stage.FileChooser;
import javafx.stage.Stage;
import javafx.util.Pair;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.regex.Pattern;
import java.util.stream.Collectors;
import java.util.stream.Stream;

public class AppController {

    public static Stage stage;

    private static String ALL_DOORS_FILTER_TEXT = "[ALL DOORS]";
    private static String ALL_CARDS_FILTER_TEXT = "[ALL CARDS]";

    private DataModel entries = new DataModel();

    @FXML public TextField lblLoadLogFile;
    @FXML public Label lblParseResult;
    @FXML public Label lblLogViewSummary;

    @FXML public TableView tblLog;

    @FXML public DateTimePicker ddLogViewFilterDateStart;
    @FXML public DateTimePicker ddLogViewFilterDateStop;
    @FXML public ComboBox ddLogViewFilterDoor;
    @FXML public ComboBox ddLogViewFilterCard;

    public void initialize() {
        ddLogViewFilterDateStart.valueProperty().addListener(
                (observable, oldValue, newValue) -> applyFilters()
        );

        ddLogViewFilterDateStart.addEventFilter(KeyEvent.KEY_PRESSED, event -> {
            if(event.getCode() == KeyCode.ENTER || event.getCode() == KeyCode.TAB) {
                applyFilters();
                event.consume();
            }
        });

        ddLogViewFilterDateStop.valueProperty().addListener(
                (observable, oldValue, newValue) -> applyFilters()
        );

        ddLogViewFilterDateStop.addEventFilter(KeyEvent.KEY_PRESSED, event -> {
            if (event.getCode() == KeyCode.ENTER || event.getCode() == KeyCode.TAB) {
                applyFilters();
                event.consume();
            }
        });

        ddLogViewFilterDoor.valueProperty().addListener(
                (observable, oldValue, newValue) -> applyFilters()
        );

        ddLogViewFilterCard.valueProperty().addListener(
                (observable, oldValue, newValue) -> applyFilters()
        );
    }

    public void browseLogFile() {
        FileChooser fc = new FileChooser();
        fc.setInitialDirectory(new File("./"));
        File file = fc.showOpenDialog(stage);
        if(file != null) {
            lblLoadLogFile.setText(file.getAbsolutePath());
        }
    }

    public HashMap<String, Object> loadSetupData() {

        HashMap<String, Object> data = new HashMap<>();
        DocumentBuilder db;

        try {
            db = DocumentBuilderFactory.newInstance().newDocumentBuilder();
            Document xml = db.parse(Files.newInputStream(Paths.get("./setup.xml")));

            NodeList tslist = xml.getElementsByTagName("timestampformat");
            List<DateTimeFormatter> tsformats = new ArrayList<>();
            for(int i = 0; i < tslist.getLength(); i++) {
                Node tsnode = tslist.item(i);
                if(tsnode.getNodeType() != Node.ELEMENT_NODE) continue;
                String tsf = tsnode.getTextContent();
                tsformats.add(DateTimeFormatter.ofPattern(tsf));
            }

            NodeList rulesetlist = xml.getElementsByTagName("ruleset");
            HashMap<String, String> colors = new HashMap<>();
            List<Pair<String, List<Pattern>>> rulesets = new ArrayList<>();
            for(int i = 0; i < rulesetlist.getLength(); i++) {
                Node rulesetnode = rulesetlist.item(i);
                if(rulesetnode.getNodeType() != Node.ELEMENT_NODE) continue;
                NodeList rulelist = rulesetlist.item(i).getChildNodes();
                List<Pattern> rules = new ArrayList<>();
                for(int j = 0; j < rulelist.getLength(); j++) {
                    Node rulenode = rulelist.item(j);
                    if(rulenode.getNodeType() != Node.ELEMENT_NODE) continue;
                    String rule = rulenode.getTextContent();
                    rules.add(Pattern.compile(rule));
                }
                rulesets.add(new Pair<>(((Element) rulesetlist.item(i)).getAttribute("name"), rules));
                colors.put(((Element) rulesetlist.item(i)).getAttribute("name"), ((Element) rulesetlist.item(i)).getAttribute("color"));
            }

            data.put("tsformats", tsformats);
            data.put("rulesets", rulesets);
            data.put("colors", colors);

            return data;

        } catch(ParserConfigurationException e) {
            e.printStackTrace();
        } catch(SAXException e) {
            e.printStackTrace();
        } catch(IOException e) {
            e.printStackTrace();
        }

        return null;
    }

    public void parseLog() {
        lblParseResult.setText("Parsing, please wait..");

        HashMap<String, Object> setupData = loadSetupData();

        final HashMap<String, String> colors = (HashMap<String, String>) setupData.get("colors");

        tblLog.setRowFactory(null);
        tblLog.setRowFactory(tv -> new TableRow<LogEntry>() {
            @Override
            protected void updateItem(LogEntry item, boolean empty) {
                super.updateItem(item, empty);
                if (item != null && colors.containsKey(item.getType())) {
                    setStyle("-fx-background-color: " + colors.get(item.getType()) + ";");
                } else {
                    setStyle("-fx-background-color: #f0f0f0");
                }
            }
        });

        FileParser fp = new FileParser((List<DateTimeFormatter>) setupData.get("tsformats"), (List<Pair<String, List<Pattern>>>) setupData.get("rulesets"));

        Integer totalLines = 0;
        List<LogEntry> parsedEntries = null;
        try {
            parsedEntries = fp.parse(lblLoadLogFile.getText());
            totalLines = parsedEntries.size();

            parsedEntries = parsedEntries.parallelStream().filter(e -> !e.getType().equals(LogEntry.EMPTY_TYPE)).collect(Collectors.toList());

            if(parsedEntries.isEmpty()) {
                lblParseResult.setText("Could not recognize any events in the file!");
                return;
            }
            entries.addAll(parsedEntries);

            lblParseResult.setText("Parsed " + totalLines + " lines OK, added " + parsedEntries.size() + " events");

            LocalDateTime minDate = entries.parallelStream().map(LogEntry::getTimestamp).min(LocalDateTime::compareTo).get();
            LocalDateTime maxDate = entries.parallelStream().map(LogEntry::getTimestamp).max(LocalDateTime::compareTo).get();

            ddLogViewFilterDateStart.setDayCellFactory(d -> new DateCell() {
                @Override public void updateItem(LocalDate item, boolean empty) {
                    super.updateItem(item, empty);
                    setDisable(item.isAfter(maxDate.toLocalDate()) || item.isBefore(minDate.toLocalDate()));
                }
            });

            ddLogViewFilterDateStop.setDayCellFactory(d -> new DateCell() {
                @Override public void updateItem(LocalDate item, boolean empty) {
                    super.updateItem(item, empty);
                    setDisable(item.isAfter(maxDate.toLocalDate()) || item.isBefore(minDate.toLocalDate()));
                }
            });

            ddLogViewFilterDateStart.setValue(minDate.toLocalDate());
            ddLogViewFilterDateStop.setValue(maxDate.toLocalDate());

            List<String> doors = entries.parallelStream().map(LogEntry::getDoor).distinct().collect(Collectors.toList());
            List<String> cards = entries.parallelStream().map(LogEntry::getCard).distinct().collect(Collectors.toList());

            Collections.sort(doors);
            Collections.sort(cards);

            doors.add(0, ALL_DOORS_FILTER_TEXT);
            cards.add(0, ALL_CARDS_FILTER_TEXT);

            ddLogViewFilterDoor.setItems(FXCollections.observableList(doors));
            ddLogViewFilterDoor.setValue(ALL_DOORS_FILTER_TEXT);

            ddLogViewFilterCard.setItems(FXCollections.observableList(cards));
            ddLogViewFilterCard.setValue(ALL_CARDS_FILTER_TEXT);

            updateTableView(entries);
            GUIUtils.autoFitTable(tblLog);

        } catch(IOException e) {
            lblParseResult.setText("I/O Error: 404");
        }
    }

    public void applyFilters() {
        Stream<LogEntry> stream = entries.parallelStream();

        LocalDateTime fromDate = ddLogViewFilterDateStart.getDateTimeValue();
        LocalDateTime toDate = ddLogViewFilterDateStop.getDateTimeValue();

        String door = (String) ddLogViewFilterDoor.getValue();
        String card = (String) ddLogViewFilterCard.getValue();

        if(fromDate == null || toDate == null || door == null || card == null) return;

        stream = stream.filter(e ->
                (e.getTimestamp().isEqual(fromDate) || e.getTimestamp().isAfter(fromDate))
                        &&
                        (e.getTimestamp().isEqual(toDate) || e.getTimestamp().isBefore(toDate))
        );

        if(!door.equals(ALL_DOORS_FILTER_TEXT)) {
            stream = stream.filter(e -> e.getDoor().equals(door));
        }

        if(!card.equals(ALL_CARDS_FILTER_TEXT)) {
            stream = stream.filter(e -> e.getCard().equals(card));
        }

        List<LogEntry> filteredEntries = stream.collect(Collectors.toList());
        updateTableView(filteredEntries);
    }

    public void updateTableView(List<LogEntry> entries) {
        tblLog.getItems().clear();
        tblLog.getItems().addAll(entries);

        if(entries.isEmpty()) {
            lblLogViewSummary.setText("No events to display using current filters");
            return;
        }

        HashMap<String, Integer> stats = new HashMap<>();

        for(LogEntry e: entries) {
            if(stats.containsKey(e.getType())) {
                stats.put(e.getType(), stats.get(e.getType()) + 1);
            } else {
                stats.put(e.getType(), 1);
            }
        }

        String eventTypeCounts = "";
        for(String s: stats.keySet()) {
            eventTypeCounts += ", " + stats.get(s) + " " + s;
        }
        eventTypeCounts = eventTypeCounts.substring(2);

        lblLogViewSummary.setText("Displaying " + entries.size() + " events: " + eventTypeCounts);
    }

    public void clearLogView() {
        entries.clear();
        lblParseResult.setText("Cleared OK");
        lblLogViewSummary.setText("");
        tblLog.getItems().clear();
        ddLogViewFilterDateStart.setDayCellFactory(null);
        ddLogViewFilterDateStart.setDateTimeValue(null);
        ddLogViewFilterDateStop.setDayCellFactory(null);
        ddLogViewFilterDateStop.setDateTimeValue(null);
        ddLogViewFilterDoor.getItems().clear();
        ddLogViewFilterCard.getItems().clear();
    }


    public void saveCurrentlyVisibleLogEntries() {
        LocalDateTime fromDate = ddLogViewFilterDateStart.getDateTimeValue();
        LocalDateTime toDate = ddLogViewFilterDateStop.getDateTimeValue();

        String door = (String) ddLogViewFilterDoor.getValue();
        String card = (String) ddLogViewFilterCard.getValue();

        if(fromDate == null || toDate == null || door == null || card == null) return;

        FileChooser fc = new FileChooser();
        fc.setInitialDirectory(new File("./"));
        String initFname = "EventLog_[" + door + "]_[" + card + "]_from_[" + fromDate.format(DateTimeFormatter.ofPattern("YYYY-MM-dd_hhmmss")) + "]_to_[" + toDate.format(DateTimeFormatter.ofPattern("YYYY-MM-dd_hhmmss")) + "].csv";
        fc.setInitialFileName(initFname);
        File file = fc.showSaveDialog(stage);
        if(file != null) {
            String csv = CSVGenerator.getLogEntryCSV(tblLog);
            try {
                Files.write(file.toPath(), csv.getBytes());
            } catch(IOException e) {
                e.printStackTrace();
            }
        }
    }

    public void saveDailyDoorStats() {
        LocalDateTime fromDate = ddLogViewFilterDateStart.getDateTimeValue();
        LocalDateTime toDate = ddLogViewFilterDateStop.getDateTimeValue();

        String door = (String) ddLogViewFilterDoor.getValue();
        String card = (String) ddLogViewFilterCard.getValue();

        if(fromDate == null || toDate == null || door == null || card == null) return;

        FileChooser fc = new FileChooser();
        fc.setInitialDirectory(new File("./"));
        String initFname = "DoorStats_[" + door + "]_[" + card + "]_from_[" + fromDate.format(DateTimeFormatter.ofPattern("YYYY-MM-dd_hhmmss")) + "]_to_[" + toDate.format(DateTimeFormatter.ofPattern("YYYY-MM-dd_hhmmss")) + "].csv";
        fc.setInitialFileName(initFname);
        File file = fc.showSaveDialog(stage);
        if(file != null) {
            String csv = CSVGenerator.getDailyStatsCSV(tblLog, LogEntry::getDoor);
            try {
                Files.write(file.toPath(), csv.getBytes());
            } catch(IOException e) {
                e.printStackTrace();
            }
        }
    }

    public void saveDailyCardStats() {
        LocalDateTime fromDate = ddLogViewFilterDateStart.getDateTimeValue();
        LocalDateTime toDate = ddLogViewFilterDateStop.getDateTimeValue();

        String door = (String) ddLogViewFilterDoor.getValue();
        String card = (String) ddLogViewFilterCard.getValue();

        if(fromDate == null || toDate == null || door == null || card == null) return;

        FileChooser fc = new FileChooser();
        fc.setInitialDirectory(new File("./"));
        String initFname = "CardStats_[" + door + "]_[" + card + "]_from_[" + fromDate.format(DateTimeFormatter.ofPattern("YYYY-MM-dd_hhmmss")) + "]_to_[" + toDate.format(DateTimeFormatter.ofPattern("YYYY-MM-dd_hhmmss")) + "].csv";
        fc.setInitialFileName(initFname);
        File file = fc.showSaveDialog(stage);
        if(file != null) {
            String csv = CSVGenerator.getDailyStatsCSV(tblLog, LogEntry::getCard);
            try {
                Files.write(file.toPath(), csv.getBytes());
            } catch(IOException e) {
                e.printStackTrace();
            }
        }
    }
}