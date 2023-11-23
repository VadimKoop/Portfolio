package app.model;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Optional;

public class LogEntry {

    public static String EMPTY_TYPE = "EMPTY";

    public static LogEntry Empty() {
        return new LogEntry(EMPTY_TYPE, Optional.empty(), Optional.empty(), Optional.empty(), Optional.empty(), Optional.empty());
    }

    private String type = EMPTY_TYPE;
    private String timestampString = "";
    private Optional<LocalDateTime> timestamp = Optional.empty();
    private Optional<String> doorInfo = Optional.empty();
    private Optional<String> door = Optional.empty();
    private Optional<String> cardInfo = Optional.empty();
    private Optional<String> card = Optional.empty();

    public LogEntry(
            String type,
            Optional<LocalDateTime> timestamp,
            Optional<String> doorInfo,
            Optional<String> door,
            Optional<String> cardInfo,
            Optional<String> card
    ) {
        this.type = type;
        this.timestamp = timestamp;
        this.timestampString = timestamp.orElse(LocalDateTime.now()).format(DateTimeFormatter.ofPattern("YYYY-MM-dd\tHH:mm:ss"));
        this.doorInfo = doorInfo;
        this.door = door;
        this.cardInfo = cardInfo;
        this.card = card;
    }

    public String getType() {
        return type;
    }

    public LocalDateTime getTimestamp() {
        return timestamp.orElse(LocalDateTime.now());
    }

    public String getTimestampString() {
        return timestampString;
    }

    public String getDoorInfo() { return doorInfo.orElse(""); }

    public String getDoor() {
        return door.orElse("");
    }

    public String getCardInfo() { return cardInfo.orElse(""); }

    public String getCard() {
        return card.orElse("");
    }

    public static String CSV_HEADER = "Timestamp,Event,Door ID,Door Info,Card ID,Card Info";

    public String toCSVRow() {
        return
                getTimestamp().format(DateTimeFormatter.ofPattern("YYYY-MM-dd HH:mm:ss"))
                        + "," + getType()
                        + "," + getDoor() + "," + getDoorInfo()
                        + "," + getCard() + "," + getCardInfo();
    }

    @Override
    public String toString() {
        return type + " " + timestamp.orElse(null) + " ";
    }
}
