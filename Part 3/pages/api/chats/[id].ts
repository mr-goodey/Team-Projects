import { NextApiRequest, NextApiResponse } from "next";
import { PrismaClient, Chat, User, ChatUser } from "@prisma/client";
import { decode, verify } from "jsonwebtoken";
const prisma = new PrismaClient();

interface TokenType {
  userId: number;
  iat: number;
  exp: number;
  theme: string;
}

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method == "GET") {
    try {
      // Get the list of all existing chats
      const { id } = req.query;
      const { userId } = req.body;
      
      const chat = await prisma.user.findUnique({
        where: {
          id: userId,
        },
        select: {
          chats: {
            where: {
              chatId: Number(id),
            },
            select: {
              chat: {
                select: {
                  id: true,
                  name: true,
                  messages: {
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
                  },
                },
              },
            },
          },
        },
      });
      // if length of chat is 0 then return error
      if (chat?.chats.length == 0) {
        return res.status(400).json({
          success: false,
          message: "Chat not found",
        });
      }
      res.status(200).json({ success: true, data: chat });
    } catch (error) {
      res.status(400).json({ success: false, message: error });
    }
  }  else if (req.method == "PUT") {
    const { id } = req.query;
    const { name, description } = req.body;
    try {
      const chat = await prisma.chat.update({
        where: {
          id: Number(id),
        },
        data: {
          name: name,
          description: description,
        },
      });
      res.status(200).json({ success: true, data: chat });
    } catch (error) {
      res.status(400).json({ success: false, message: error });
    }
  } else if (req.method == "DELETE") {
    const { id } = req.query;
    try {
      const chat = await prisma.chat.delete({
        where: {
          id: Number(id),
        },
      });
      res.status(200).json({ success: true, data: chat });
    } catch (error) {
      res.status(400).json({ success: false, message: error });
    }
  } else {
    return res.status(400).json({ success: false, message: "invalid request" });
  }
}
