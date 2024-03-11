/** CW12 lombok.*/
package dto;

import lombok.Builder;
import lombok.Getter;
import lombok.Setter;

@Builder
@Getter
@Setter
public class OrderDtoMockedBuilderAndFactory {

      // Private can be change only inside the class or trough Setters.
      private String status;
      private int courierId;
      private String customerName;
      private String customerPhone;
      public String comment;
      long id;

      public String getCustomerName() {
            return customerName;
      }

      public String getStatus() {
            return status;
      }

      public static OrderDtoMockedBuilderAndFactory createRandomOrder () {
            // builder
            return OrderDtoMockedBuilderAndFactory.builder()
                    .status("OPEN")
                    .courierId(5)
                    .customerName("Vadim")
                    .comment("Lombok plugin is on the way!")
                    .id(6)
                    .build();
      }
}
