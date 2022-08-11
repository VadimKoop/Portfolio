/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ee.ttu.idu0075.vehicles;

import ee.ttu.idu0075._2016.ws._142676.vadim.*;
import java.math.BigDecimal;
import java.math.BigInteger;
import javax.ws.rs.core.Context;
import javax.ws.rs.core.UriInfo;
import javax.ws.rs.Produces;
import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.PUT;
import javax.ws.rs.PathParam;
import javax.ws.rs.QueryParam;
import javax.ws.rs.core.MediaType;

/**
 * REST Web Service
 *
 * @author Vadim
 */
@Path("vehicles")
public class VehicleResource {
    
    private static final VehicleLoanService WS = new VehicleLoanService(); /** has own static web service */

    @Context
    private UriInfo context;

    /**
     * Creates a new instance of VehicleResource
     */
    public VehicleResource() {
    }

    @Path("{id}")
    @GET                    /** method GET or POST*/
    @Produces(MediaType.APPLICATION_JSON)   /** Giving JSON */
    public VehicleType getVehicle(@QueryParam("token") String token, @PathParam("id") BigInteger id) { /** 1. giving back getVehicle */
        GetVehicleRequest r = new GetVehicleRequest(); /** Creating request for SOAP service*/ /**token taking from command line, id from way */
        r.setToken(token);
        r.setId(id);
        return WS.getVehicle(r);
    }

    @Path("add")
    @POST
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public VehicleType addVehicle(@QueryParam("token") String token, @QueryParam("opId") String opId, VehicleType vehicle) {
        AddVehicleRequest request = new AddVehicleRequest();
        request.setToken(token);
        request.setOperationId(opId);
        request.setName(vehicle.getName());
        request.setType(vehicle.getType());
        request.setColor(vehicle.getColor());
        request.setComment(vehicle.getComment());
        return WS.addVehicle(request);
    }

    @Path("list")
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public VehicleListType getVehicleList(@QueryParam("token") String token, @QueryParam("type") TransportType type) {
        GetVehicleListRequest request = new GetVehicleListRequest();
        request.setToken(token);
        request.setType(type);
        return WS.getVehicleList(request);
    }    
}
