/** CW11 Constructors and data.*/
package dto;

public class OrderDtoMocked {

      // Private can be change only inside the class or trough Setters.
      private String status;
      private int courierId;
      private String customerName;
      private  String customerPhone;
      public String comment;
      int id;



      public OrderDtoMocked(String status, int courierId, String customerName, String customerPhone, String comment, int id) {
            this.status = status;
            this.courierId = courierId;
            this.customerName = customerName;
            this.customerPhone = customerPhone;
            this.comment = comment;
            this.id = id;
      }

      // Empty constructor. In Java classes we can have unlimited number of constructors.
      public OrderDtoMocked() {

      }


      public String getStatus() {
            return status;
      }

      public void setStatus(String status) {
            this.status = status;
      }

      public int getCourierId() {
            return courierId;
      }

      public void setCourierId(int courierId) {
            this.courierId = courierId;
      }

      public String getCustomerName() {
            return customerName;
      }

      public void setCustomerName(String customerName) {
            this.customerName = customerName;
      }

      public String getCustomerPhone() {
            return customerPhone;
      }

      public void setCustomerPhone(String customerPhone) {
            this.customerPhone = customerPhone;
      }

      public String getComment() {
            return comment;
      }

      public void setComment(String comment) {
            this.comment = comment;
      }

      public int getId() {
            return id;
      }

      public void setId(int id) {
            this.id = id;
      }
}
