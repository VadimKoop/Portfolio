/**HW14 */
package delivery.dto;

import delivery.utils.RandomDataGenerator;
import lombok.Builder;


@Builder

public class OrderDto {

    String status;
    int courierId;
    String customerName;
    String customerPhone;
    String comment;
    long id;


    public static OrderDto createRandomOrderAndFixedOrderStatus() {
        return OrderDto.builder()
                .customerName(RandomDataGenerator.generateName())
                .customerPhone(RandomDataGenerator.generatePhoneNumber())
                .comment(RandomDataGenerator.generateComment())
                .courierId(RandomDataGenerator.generateCourierId())
                .id(RandomDataGenerator.generateOrderId())
                .status("OPEN")
                .build();
    }
    public static OrderDto createRandomOrderWithRandomStatus() {
        return OrderDto.builder()
                .customerName(RandomDataGenerator.generateName())
                .customerPhone(RandomDataGenerator.generatePhoneNumber())
                .comment(RandomDataGenerator.generateComment())
                .courierId(RandomDataGenerator.generateCourierId())
                .id(RandomDataGenerator.generateOrderId())
                .status(RandomDataGenerator.generateRandomOrderStatus())
                .build();
    }
}