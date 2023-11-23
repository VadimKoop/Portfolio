/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package client;

import javafx.application.Application;
import javafx.event.ActionEvent;
import javafx.event.EventHandler;
import javafx.scene.Scene;
import javafx.scene.control.Button;
import javafx.scene.control.TextField;
import javafx.scene.layout.Pane;
import javafx.stage.Stage;
import javax.xml.soap.SOAPBody;
import javax.xml.soap.SOAPMessage;
import org.w3c.dom.NodeList;

/**
 *
 * @author Vadim
 */
public class Client extends Application {
    
    SOAPService soap = new SOAPService("AUTO", "http://desktop-jcmmoh1:8080/VehicleLoanService/VehicleLoanService", "vad", "http://www.ttu.ee/idu0075/2016/ws/142676/vadim");
    
    public Client() {}
    
    @Override
    public void start(Stage primaryStage) {
                
        TextField nameAdd = new TextField();
        nameAdd.setLayoutX(25);
        nameAdd.setLayoutY(60);
        nameAdd.setPromptText("Name");
        
        TextField typeAdd = new TextField();
        typeAdd.setLayoutX(25);
        typeAdd.setLayoutY(90);
        typeAdd.setPromptText("Type");

        TextField colorAdd = new TextField();
        colorAdd.setLayoutX(25);
        colorAdd.setLayoutY(120);
        colorAdd.setPromptText("Color");
        
        TextField commentAdd = new TextField();
        commentAdd.setLayoutX(25);
        commentAdd.setLayoutY(150);        
        commentAdd.setPromptText("Comment");
        
        TextField idAdd = new TextField();
        idAdd.setLayoutX(200);
        idAdd.setLayoutY(150);
        idAdd.setEditable(false);
        idAdd.setPromptText("Result");
        
        Button btnAdd = new Button();
        btnAdd.setText("Add Vehicle");
        btnAdd.setOnAction(new EventHandler<ActionEvent>() {
            
            @Override
            public void handle(ActionEvent event) { /* push button add*/
                try {
                    SOAPMessage response = soap.addVehicle(nameAdd.getText(), colorAdd.getText(), typeAdd.getText(), commentAdd.getText());
                    nameAdd.setText("");
                    colorAdd.setText("");
                    typeAdd.setText("");
                    commentAdd.setText("");
                    SOAPBody body = response.getSOAPPart().getEnvelope().getBody();
                    NodeList main = body.getElementsByTagName("addVehicleResponse");
                    idAdd.setText("ERROR");
                    if(main.getLength() > 0) {
                        NodeList ans = main.item(0).getChildNodes();
                        for(int i = 0; i < ans.getLength(); i++) {
                            if(ans.item(i).getNodeName().toLowerCase() == "id") { /* find id from response*/
                                idAdd.setText(ans.item(i).getTextContent()); /* if find put instead error */
                            }
                        }
                    }                    
                } catch(Exception e) {
                    e.printStackTrace();
                }
            }
        });
        btnAdd.setLayoutX(25);
        btnAdd.setLayoutY(25);
        
        TextField idGet = new TextField();
        idGet.setLayoutX(25);
        idGet.setLayoutY(260);
        idGet.setPromptText("ID");
        
        TextField nameGet = new TextField();
        nameGet.setLayoutX(200);
        nameGet.setLayoutY(260);
        nameGet.setEditable(false);
        nameGet.setPromptText("Name");
        
        TextField typeGet = new TextField();
        typeGet.setLayoutX(200);
        typeGet.setLayoutY(290);
        typeGet.setEditable(false);
        typeGet.setPromptText("Type");

        TextField colorGet = new TextField();
        colorGet.setLayoutX(200);
        colorGet.setLayoutY(320);
        colorGet.setEditable(false);
        colorGet.setPromptText("Color");
                
        TextField commentGet = new TextField();
        commentGet.setLayoutX(200);
        commentGet.setLayoutY(350);
        commentGet.setEditable(false);
        commentGet.setPromptText("Comment");
        
        Button btnGet = new Button();
        btnGet.setText("Get Vehicle");
        btnGet.setOnAction(new EventHandler<ActionEvent>() {
            
            @Override
            public void handle(ActionEvent event) {
                try {
                    SOAPMessage response = soap.getVehicle(idGet.getText());
                    nameGet.setText("");
                    typeGet.setText("");
                    colorGet.setText("");
                    commentGet.setText("");                        
                    SOAPBody body = response.getSOAPPart().getEnvelope().getBody();
                    NodeList main = body.getElementsByTagName("getVehicleResponse");
                    boolean noError = false;
                    if(main.getLength() > 0) {
                        NodeList ans = main.item(0).getChildNodes();
                        for(int i = 0; i < ans.getLength(); i++) {
                            switch(ans.item(i).getNodeName().toLowerCase()) { /*checking names */
                                case "name":
                                    nameGet.setText(ans.item(i).getTextContent());
                                    break;
                                case "type":
                                    typeGet.setText(ans.item(i).getTextContent());
                                    break;
                                case "color":
                                    colorGet.setText(ans.item(i).getTextContent());
                                    break;
                                case "comment":
                                    commentGet.setText(ans.item(i).getTextContent());
                                    break;
                                case "id":
                                    noError = true; /*if find id it is in system */
                                    break;
                            }
                        }
                    }
                    if(!noError) {          /* =if error*/
                        nameGet.setText("ERROR");
                        typeGet.setText("ERROR");
                        colorGet.setText("ERROR");
                        commentGet.setText("ERROR");
                    }
                } catch(Exception e) {
                    e.printStackTrace();
                }
            }
        });
        btnGet.setLayoutX(25);
        btnGet.setLayoutY(220);
        
        Pane root = new Pane();
        root.getChildren().add(nameAdd);
        root.getChildren().add(typeAdd);
        root.getChildren().add(colorAdd);
        root.getChildren().add(commentAdd);
        root.getChildren().add(btnAdd);
        root.getChildren().add(idAdd);
        root.getChildren().add(btnGet);
        root.getChildren().add(idGet);
        root.getChildren().add(nameGet);
        root.getChildren().add(typeGet);
        root.getChildren().add(colorGet);
        root.getChildren().add(commentGet);
        
        Scene scene = new Scene(root, 360, 400);
        
        primaryStage.setTitle("Super Mega Vadim Vehicle Loan Service Client");
        primaryStage.setScene(scene);
        primaryStage.show();
    }

    /**
     * @param args the command line arguments
     */
    public static void main(String[] args) {
        launch(args);
    }
    
}
