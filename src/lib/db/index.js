import { drizzle } from "drizzle-orm/mysql2";
import mysql from "mysql2/promise";
import * as schema from "./schema.js";

// Create a connection pool to MySQL
const poolConnection = mysql.createPool(
  process.env.DATABASE_URL || "mysql://user:password@localhost:3306/kasibuy"
);

// Export the db instance to be used throughout the application
export const db = drizzle(poolConnection, { schema, mode: "default" });
