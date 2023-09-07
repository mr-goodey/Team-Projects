import { PrismaClient } from "@prisma/client";
import { NextApiRequest, NextApiResponse } from "next";

const prisma = new PrismaClient();

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method == "GET") {
    // Get a list of users based on the search query
    const { search = "" } = req.query;
    const users = await prisma.user.findMany({
      take: 10,
      where: {
        OR: [
          {
            username: {
              contains: search as string,
            },
          },
          {
            email: {
              contains: search as string,
            },
          },
        ],
      },
      select: {
        id: true,
        username: true,
        email: true,
      },
    });
    res.status(200).json({
      success: true,
      data: users,
    });
  } else {
    res.status(400).json({
      success: false,
      message: "Invalid request method",
    });
  }
}
