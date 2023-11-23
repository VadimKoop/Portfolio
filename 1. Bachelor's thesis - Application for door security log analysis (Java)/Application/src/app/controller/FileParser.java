package app.controller;

import app.model.LogEntry;
import javafx.util.Pair;

import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;
import java.util.List;
import java.util.Optional;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

public class FileParser {

    private List<DateTimeFormatter> tsFormatters;
    private List<Pair<String, List<Pattern>>> rulesets;

    public FileParser(
            List<DateTimeFormatter> tsFormatters,
            List<Pair<String, List<Pattern>>> rulesets
    ) {
        this.tsFormatters = tsFormatters;
        this.rulesets = rulesets;
    }

    private Optional<String> tryFetchString(Matcher m, String name) {
        Optional<String> group = Optional.empty();
        try {
            group = Optional.of(m.group(name));
        } catch(IllegalArgumentException e)  {
            group = Optional.empty();
        }
        return group;
    }

    private Optional<LogEntry> tryParseLineUsingPatterns(
            String line,
            String event,
            List<Pattern> patterns
    ) {
        for(Pattern p: patterns) {
            Matcher m = p.matcher(line);
            if(m.matches()) {
                Optional<LocalDateTime> ts = Optional.empty();
                for(DateTimeFormatter dtf : tsFormatters) {
                    try {
                        ts = Optional.of(LocalDateTime.parse(m.group("ts"), dtf));
                    } catch(DateTimeParseException e) {
                        ts = Optional.empty();
                    }
                    if(ts.isPresent()) break;
                }
                Optional<String> doorInfo = tryFetchString(m, "doorinfo");
                Optional<String> door = tryFetchString(m, "door");
                Optional<String> cardInfo = tryFetchString(m, "cardinfo");
                Optional<String> card = tryFetchString(m, "card");

                return Optional.of(new LogEntry(event, ts, doorInfo, door, cardInfo, card));
            }
        }
        return Optional.empty();
    }

    private LogEntry parseLine(String line) {

        Optional<LogEntry> entry = Optional.empty();
        for(Pair<String, List<Pattern>> ruleset : rulesets) {
            entry = tryParseLineUsingPatterns(line, ruleset.getKey(), ruleset.getValue());
            if(entry.isPresent()) {
                return entry.get();
            }
        }

        return LogEntry.Empty();
    }

    public List<LogEntry> parse(String fname) throws IOException {
        return Files.lines(Paths.get(fname), Charset.forName("UTF-8")).parallel().map(this::parseLine).collect(Collectors.toList());
    }
}
