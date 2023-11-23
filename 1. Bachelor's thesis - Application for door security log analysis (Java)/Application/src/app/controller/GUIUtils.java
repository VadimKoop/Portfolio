package app.controller;

/**
 * SOURCE: https://stackoverflow.com/questions/14650787/javafx-column-in-tableview-auto-fit-size
 */

import com.sun.javafx.scene.control.skin.TableViewSkin;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

public class GUIUtils {
    private static Method columnToFitMethod;

    static {
        try {
            columnToFitMethod = TableViewSkin.class.getDeclaredMethod("resizeColumnToFitContent", TableColumn.class, int.class);
            columnToFitMethod.setAccessible(true);
        } catch (NoSuchMethodException e) {
            e.printStackTrace();
        }
    }

    public static void autoFitTable(TableView tableView) {
        for (Object column : tableView.getColumns()) {
            try {
                columnToFitMethod.invoke(tableView.getSkin(), column, -1);
            } catch (IllegalAccessException | InvocationTargetException e) {
                e.printStackTrace();
            }
        }
    }
}