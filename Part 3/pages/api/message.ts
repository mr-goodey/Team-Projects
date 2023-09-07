import { PrismaClient } from "@prisma/client";
import { NextApiRequest, NextApiResponse } from "next";

const prisma = new PrismaClient();

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method == "POST") {
    try {
      const { chatId, userId, text } = req.body;

      const message = await prisma.message.create({
        data: {
          text,
          chatId: Number(chatId),
          userId: Number(userId),
        },
        select: {
          text: true,
          timestamp: true,
          user: {
            select: {
              username: true,
              id: true,
            },
          },
        },
      });

      res.status(200).json({
        success: true,
        message,
      });
    } catch (error) {
      res.status(400).json({ success: false, message: error });
    }
  } else {
    res.status(400).json({ success: false, message: "invalid request" });
  }
}
