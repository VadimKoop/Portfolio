/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ee.ttu.idu0075.vehicles;

import ee.ttu.idu0075._2016.ws._142676.vadim.*;
import java.math.BigDecimal;
import java.math.BigInteger;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.GregorianCalendar;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.ws.rs.core.Context;
import javax.ws.rs.core.UriInfo;
import javax.ws.rs.Consumes;
import javax.ws.rs.Produces;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PUT;
import javax.ws.rs.PathParam;
import javax.ws.rs.QueryParam;
import javax.ws.rs.core.MediaType;
import javax.xml.datatype.DatatypeConfigurationException;
import javax.xml.datatype.DatatypeFactory;
import javax.xml.datatype.XMLGregorianCalendar;

/**
 * REST Web Service
 *
 * @author Vadim
 */
@Path("loans")
public class LoanResource {

    private static VehicleLoanService WS = new VehicleLoanService();
   
    
    @Context
    private UriInfo context;

    /**
     * Creates a new instance of LoanResource
     */
    public LoanResource() {
    }

    @Path("{id}")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public LoanType getLoan(@QueryParam("token") String token, @PathParam("id") BigInteger id) {
        GetLoanRequest request = new GetLoanRequest();
        request.setToken(token);
        request.setId(id);
        return WS.getLoan(request);
    }

    @Path("add")
    @POST
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public LoanType addLoan(@QueryParam("token") String token, @QueryParam("opId") String opId, LoanType loan) {
        AddLoanRequest request = new AddLoanRequest();
        request.setToken(token);
        request.setOperationId(opId);
        request.setCustomerName(loan.getCustomerName());
        request.setOrderDate(loan.getOrderDate());
        request.setReturnDate(loan.getReturnDate());
        request.setComment(loan.getComment());        
        return WS.addLoan(request);
    }

    @Path("list")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public LoanListType getLoanList(@QueryParam("token") String token, @QueryParam("startDate") String start, @QueryParam("endDate") String end, @QueryParam("hasRelatedVehicles") String hasRelatedVehicles) {
        GetLoanListRequest r = new GetLoanListRequest();
        r.setToken(token);
        r.setStartOrderDate(parseDate(start));
        r.setEndOrderDate(parseDate(end));
        r.setHasRelatedVehicles(hasRelatedVehicles);
        return WS.getLoanList(r);
    }
    
    @Path("vehicles")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public LoanVehicleListType getLoanVehicleList(@QueryParam("token") String token, @QueryParam("id") BigInteger id) {
        GetLoanVehicleListRequest r = new GetLoanVehicleListRequest();
        r.setToken(token);
        r.setId(id);
        return WS.getLoanVehicleList(r);
    }
        
    @Path("addvehicle")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public LoanVehicleType addVehicleToLoan(@QueryParam("token") String token, @QueryParam("opId") String opId, @QueryParam("vehicle") BigInteger vehicle, @QueryParam("loan") BigInteger loan, @QueryParam("quantity") BigInteger quantity, @QueryParam("price") BigDecimal price) {
        AddVehicleToLoanRequest request = new AddVehicleToLoanRequest();
        request.setToken(token);
        request.setOperationId(opId);
        request.setVehicleId(vehicle);
        request.setLoanId(loan);
        request.setQuantity(quantity);
        request.setUnitPrice(price);
        return WS.addVehicleToLoan(request);
    }
        
    private XMLGregorianCalendar parseDate(String string) {
        try {
            GregorianCalendar calendar = new GregorianCalendar();
            calendar.setTime(new SimpleDateFormat("yyyy-mm-dd").parse(string));
            return DatatypeFactory.newInstance().newXMLGregorianCalendar(calendar);
        } catch(ParseException | DatatypeConfigurationException ex) {
            Logger.getLogger(LoanResource.class.getName()).log(Level.SEVERE, null, ex);
            return null;
        }
    }
}
