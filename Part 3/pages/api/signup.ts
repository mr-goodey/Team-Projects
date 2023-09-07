import { NextApiRequest, NextApiResponse } from "next";
import jwt from "jsonwebtoken";
import bcrypt from "bcrypt";
import { PrismaClient, Prisma } from "@prisma/client";

const prisma = new PrismaClient();

export default async function signupHandler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method Not Allowed" });
  }

  const { username, email, password } = req.body;

  try {
    // Hash the provided password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Create the user in the database
    const user = await prisma.user.create({
      data: {
        username,
        email,
        password: hashedPassword,
      },
    });

    // Generate a JWT token, need to create a jwt secret passkey
    const token = jwt.sign(
      { userId: user.id, theme: user.theme },
      "your_jwt_secret",
      { expiresIn: "1h" }
    );

    // Send the token back in the response
    res.status(200).json({ token });
  } catch (error) {
    // If email is already in use, return error
    if (
      error instanceof Prisma.PrismaClientKnownRequestError &&
      error.code === "P2002"
    ) {
      return res.status(409).json({ message: "Email already in use" });
    }

    console.error(error);
    res.status(500).json({ message: "Internal Server Error" });
  }
}
