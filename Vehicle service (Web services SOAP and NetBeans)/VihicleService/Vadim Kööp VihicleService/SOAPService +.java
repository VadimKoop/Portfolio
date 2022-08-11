/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package client;

import javax.xml.soap.MessageFactory;
import javax.xml.soap.SOAPBody;
import javax.xml.soap.SOAPConnection;
import javax.xml.soap.SOAPConnectionFactory;
import javax.xml.soap.SOAPElement;
import javax.xml.soap.SOAPEnvelope;
import javax.xml.soap.SOAPException;
import javax.xml.soap.SOAPMessage;
import javax.xml.soap.SOAPPart;

/**
 *
 * @author Vadim
 */
public class SOAPService {

    private final String token;
    private final String url;
    private final String tns; /* Target namespace = vad*/
    private final String namespace;
    
    public SOAPService(String token, String url, String tns, String namespace) {
        this.token = token;
        this.url = url;
        this.tns = tns;
        this.namespace = namespace;
    }
    
    public SOAPMessage getVehicle(String id) { /* Internet HERO*/
        try {
            MessageFactory factory = MessageFactory.newInstance();            
            SOAPMessage request = factory.createMessage();            
            SOAPPart part = request.getSOAPPart();
            SOAPEnvelope envelope = part.getEnvelope();            
            SOAPBody body = envelope.getBody();
            envelope.addNamespaceDeclaration(tns, namespace);
            SOAPElement main = body.addChildElement("getVehicleRequest", tns);
            main.addChildElement("token", tns).addTextNode(token);    
            main.addChildElement("id", tns).addTextNode(id);    
            request.saveChanges();
            SOAPConnectionFactory connectionFactory = SOAPConnectionFactory.newInstance();
            SOAPConnection connection = connectionFactory.createConnection();
            SOAPMessage response = connection.call(request, url); /* */
            connection.close();
            return response;            
        } catch(SOAPException e) {
            e.printStackTrace();
        }
        return null;
    }  
      
    public SOAPMessage addVehicle(String name, String color, String type, String comment) {
        try {
            MessageFactory factory = MessageFactory.newInstance();            
            SOAPMessage request = factory.createMessage();            
            SOAPPart part = request.getSOAPPart();
            SOAPEnvelope envelope = part.getEnvelope();            
            SOAPBody body = envelope.getBody();
            envelope.addNamespaceDeclaration(tns, namespace);
            SOAPElement main = body.addChildElement("addVehicleRequest", tns);
            Double random = Math.random()*Double.MAX_VALUE;
            String opId = random.toString();                    /* random number to string*/
            main.addChildElement("token", tns).addTextNode(token);    
            main.addChildElement("operationId", tns).addTextNode(opId);
            main.addChildElement("name", tns).addTextNode(name);    
            main.addChildElement("color", tns).addTextNode(color);    
            main.addChildElement("type", tns).addTextNode(type);    
            main.addChildElement("comment", tns).addTextNode(comment);
            request.saveChanges();
            SOAPConnectionFactory connectionFactory = SOAPConnectionFactory.newInstance();
            SOAPConnection connection = connectionFactory.createConnection();
            SOAPMessage response = connection.call(request, url);
            connection.close();
            return response;                        
        } catch(SOAPException e) {
            e.printStackTrace();
        }
        return null;
    }
}