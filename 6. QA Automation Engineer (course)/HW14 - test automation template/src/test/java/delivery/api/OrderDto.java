/**HW14 */
package delivery.dto;

import groovy.transform.builder.Builder;
import io.restassured.response.ValidatableResponseLogSpec;

@Builder
public class OrderDto {
    public String status;

    public int curierId;
    public String customerName;

    public String customerPhone;

    public String comment;

    public int id;

    public OrderDto(String status, int curierId, String customerName, String customerPhone, String comment, int id) {
        this.status = status;
        this.curierId = curierId;
        this.customerName = customerName;
        this.customerPhone = customerPhone;
        this.comment = comment;
        this.id = id;
    }

}