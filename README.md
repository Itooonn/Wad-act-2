# WAD ACTIVTY 2

![My Image](https://drive.google.com/uc?export=view&id=14xUGDRayxBtg20Uxd5Ya3aRSuCB6T4oZ)

# Database fields used

| Relationship | Entities | Logic | Laravel Method |
|----------|----------|----------|----------|
| One-to-One    | `Customer` <-> `Account` | One Customer can only have one account   | `HasOne()` / `HasMany()`   |
| One-to-Many    | `Customer` <-> `Orders`  | One Customer can have many orders   | `HasMany()` / `BelongsTo()`  |
| Many-to-Many    | `Orders` <-> `Product`  | One order can contain many products, and one product can belong to many orders.   | `BelongsToMany()`   |
