import { NextApiRequest, NextApiResponse } from "next";
import { PrismaClient, Chat, User, ChatUser } from "@prisma/client";
import { decode, JwtPayload, verify } from "jsonwebtoken";

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
      let { userId } = req.body;

      const chats = await prisma.user.findUnique({
        where: {
          id: parseInt(userId as string),
        },
        select: {
          chats: {
            select: {
              chat: {
                select: {
                  id: true,
                  name: true,
                  description: true,
                  users: {
                    select: {
                      user: {
                        select: {
                          username: true,
                          id: true,
                        },
                      },
                    },
                  },
                  messages: {
                    take: 1,
                    orderBy: {
                      timestamp: "desc",
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
                  },
                },
              },
            },
          },
        },
      });

      res.status(200).json({ success: true, data: chats });
    } catch (e) {
      res.status(400).json({ success: false, message: e });
    }
  } else if (req.method == "POST") {
    try {
      const { users, userId } = req.body;
      const chat = await prisma.chat.create({
        data: {
          name: "New Chat",
          users: {
            create: [
              ...users.map((user: any) => ({
                userId: user.id,
              })),
              { userId: Number(userId) },
            ],
          },
        },
      });
      res.status(200).json({ success: true, data: chat });
    } catch (error) {
      res.status(400).json({ success: false, message: error });
    }
  } else if (req.method == "PUT") {
    // To update the prisma of new created chat
  } else if (req.method == "DELETE") {
    // To delete chats
  } else {
    return res.status(400).json({ success: false, message: "invalid request" });
  }
}
