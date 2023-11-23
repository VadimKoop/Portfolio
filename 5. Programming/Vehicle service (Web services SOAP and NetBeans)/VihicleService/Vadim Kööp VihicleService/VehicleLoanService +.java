/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ee.ttu.idu0075.vehicles;


import ee.ttu.idu0075._2016.ws._142676.vadim.*;
import java.math.BigDecimal;
import java.math.BigInteger;
import java.util.HashMap;
import java.util.Map;
import javax.jws.WebService;
import javax.xml.datatype.DatatypeConstants;
import javax.xml.datatype.XMLGregorianCalendar;

/**
 *
 * @author Vadim
 */
@WebService(serviceName = "VehicleLoanService", portName = "LoanPort", endpointInterface = "ee.ttu.idu0075._2016.ws._142676.vadim.LoanPortType", targetNamespace = "http://www.ttu.ee/idu0075/2016/ws/142676/vadim", wsdlLocation = "WEB-INF/wsdl/VehicleLoanService/VehicleLoanService.wsdl")
public class VehicleLoanService {
    
    private static String TOKEN = "AUTO";
    
    private static Map<BigInteger, VehicleType> VEHICLES = new HashMap<>();
    private static Map<BigInteger, LoanType> LOANS = new HashMap<>();
    
    private boolean checkToken(String token) {
        return token.equalsIgnoreCase(TOKEN);
    }
    
    private boolean checkQuantity(BigInteger quantity) {
        return quantity.compareTo(BigInteger.ZERO) > 0;
    }
    
    private boolean checkPrice(BigDecimal price) {
        return price.compareTo(BigDecimal.ZERO) >= 0;
    }
    
    private boolean checkNameNotEmpty(String name) {
        return name.length() > 0;
    }
    
    private boolean checkRelatedVehicles(String hasRelatedVehicles) {
        if(hasRelatedVehicles == null) return true;
        else if(hasRelatedVehicles.equalsIgnoreCase("YES")) return true;
        else if(hasRelatedVehicles.equalsIgnoreCase("NO")) return true;
        else return false;
    }
    
    private int compareDates(XMLGregorianCalendar first, XMLGregorianCalendar second) {
        if(first.compare(second) == DatatypeConstants.LESSER && second.compare(first) == DatatypeConstants.GREATER) return 1;
        if(first.compare(second) == DatatypeConstants.EQUAL && second.compare(first) == DatatypeConstants.EQUAL) return 0;
        return -1;
    }
    
    private static Map<String, VehicleType> ADD_VEHICLE_RESPONSES = new HashMap<>();
    private static Map<String, LoanType> ADD_LOAN_RESPONSES = new HashMap<>();    
    private static Map<String, LoanVehicleType> ADD_VEHICLE_TO_LOAN_RESPONSES = new HashMap<>();

    public VehicleType getVehicle(GetVehicleRequest parameter) {
        if(checkToken(parameter.getToken())) {
            BigInteger id = parameter.getId();
            if(VEHICLES.containsKey(id)) return VEHICLES.get(id);
        };
        return null;
    }

    public VehicleType addVehicle(AddVehicleRequest parameter) {
        if(checkToken(parameter.getToken())) {
            String opId = parameter.getOperationId();
            String name = parameter.getName();
            String comment = parameter.getComment();
            String color = parameter.getColor();
            TransportType type = parameter.getType();
            
            if(ADD_VEHICLE_RESPONSES.containsKey(opId)) return ADD_VEHICLE_RESPONSES.get(opId);
            if(!checkNameNotEmpty(name)) return null;
            
            VehicleType veh = new VehicleType();
            BigInteger id = makeNewVehicleId();
            veh.setId(id);
            veh.setName(name);
            veh.setComment(comment);
            veh.setColor(color);
            veh.setType(type);
            VEHICLES.put(id, veh);
            ADD_VEHICLE_RESPONSES.put(opId, veh);
            
            return veh;
        }
        return null;        
    }

    public VehicleListType getVehicleList(GetVehicleListRequest parameter) {
        if(checkToken(parameter.getToken())) {
            TransportType type = parameter.getType();
            VehicleListType vehList = new VehicleListType();
            
            for(VehicleType veh : VEHICLES.values()) {
                if(type == null || veh.getType() == type) {
                    vehList.getVehicle().add(veh);
                }
            }
            return vehList;            
        }
        return null;   
    }

    public LoanType getLoan(GetLoanRequest parameter) {
        if(checkToken(parameter.getToken())) {        
            BigInteger id = parameter.getId();
            if(LOANS.containsKey(id)) return LOANS.get(id);
        }
        return null;
    }

    public LoanType addLoan(AddLoanRequest parameter) {
        if(checkToken(parameter.getToken())) {     
            String opId = parameter.getOperationId();
            String name = parameter.getCustomerName();
            String comment = parameter.getComment();
            XMLGregorianCalendar orderDate = parameter.getOrderDate();
            XMLGregorianCalendar returnDate = parameter.getReturnDate();
                    
            if(ADD_LOAN_RESPONSES.containsKey(opId)) return ADD_LOAN_RESPONSES.get(opId);
            if(!checkNameNotEmpty(name)) return null;
            if(compareDates(orderDate, returnDate) < 0) return null;
            
            LoanType loan = new LoanType();
            BigInteger id = makeNewLoanId();
            loan.setId(id);
            loan.setCustomerName(name);
            loan.setComment(comment);
            loan.setOrderDate(orderDate);
            loan.setReturnDate(returnDate);
            loan.setVehicles(new LoanVehicleListType());
            LOANS.put(id, loan);
            ADD_LOAN_RESPONSES.put(opId, loan);

            return loan;        
        }
        return null;
    }

    public LoanListType getLoanList(GetLoanListRequest parameter) {
        if(checkToken(parameter.getToken())) {     
            XMLGregorianCalendar startOrderDate = parameter.getStartOrderDate();
            XMLGregorianCalendar endOrderDate = parameter.getEndOrderDate();
            String hasVehicles = parameter.getHasRelatedVehicles();

            if(!checkRelatedVehicles(hasVehicles)) return null;
            if(compareDates(startOrderDate, endOrderDate) < 0) return null;
            
            LoanListType loanList = new LoanListType();        
            
            for(LoanType loan : LOANS.values()) {
                if(hasVehicles == null || (hasVehicles.equalsIgnoreCase("NO") && loan.getVehicles().getVehicle().isEmpty()) || (hasVehicles.equalsIgnoreCase("YES") && !loan.getVehicles().getVehicle().isEmpty())) {
                    XMLGregorianCalendar orderDate = loan.getOrderDate();
                    if(compareDates(startOrderDate, orderDate) >= 0 && compareDates(orderDate, endOrderDate) >= 0) {
                        loanList.getLoan().add(loan);                    
                    }
                }
            }
            return loanList;
        }
        return null;
    }

    public LoanVehicleListType getLoanVehicleList(GetLoanVehicleListRequest parameter) {
        if(checkToken(parameter.getToken())) {     
            BigInteger id = parameter.getId();
            
            if(!LOANS.containsKey(id)) return null;
            
            LoanVehicleListType loanVehicleList = new LoanVehicleListType();
            loanVehicleList.getVehicle().addAll(LOANS.get(id).getVehicles().getVehicle());
            return loanVehicleList;        
        }
        return null;
    }

    public LoanVehicleType addVehicleToLoan(AddVehicleToLoanRequest parameter) {
        if(checkToken(parameter.getToken())) {    
            String opId = parameter.getOperationId();
            BigInteger loanId = parameter.getLoanId();
            BigInteger vehId = parameter.getVehicleId();
            BigInteger quantity = parameter.getQuantity();
            BigDecimal unitPrice = parameter.getUnitPrice();

            if(ADD_VEHICLE_TO_LOAN_RESPONSES.containsKey(opId)) return ADD_VEHICLE_TO_LOAN_RESPONSES.get(opId);
            
            if(!VEHICLES.containsKey(vehId)) return null;
            if(!LOANS.containsKey(loanId)) return null;
            if(!checkQuantity(quantity)) return null;
            if(!checkPrice(unitPrice)) return null;

            LoanVehicleType loanVehicle = new LoanVehicleType();
            loanVehicle.setVehicle(VEHICLES.get(vehId));
            loanVehicle.setQuantity(quantity);
            loanVehicle.setUnitPrice(unitPrice);

            LOANS.get(loanId).getVehicles().getVehicle().add(loanVehicle);
            ADD_VEHICLE_TO_LOAN_RESPONSES.put(opId, loanVehicle);
            return loanVehicle;            
        }
        return null;
    }

    private static BigInteger vehicleId = BigInteger.ZERO;
    
    private static BigInteger loanId = BigInteger.ZERO;
    
    private BigInteger makeNewVehicleId() {
        vehicleId = vehicleId.add(BigInteger.ONE);
        return vehicleId;
    }
    
    private BigInteger makeNewLoanId() {
        loanId = loanId.add(BigInteger.ONE);
        return loanId;
    }    
}
