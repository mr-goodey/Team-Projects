import { NextApiRequest, NextApiResponse } from "next";
//change tables you wish to import
import { PrismaClient, User, Task, Project } from "@prisma/client";
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
    if (req.method != "GET"){ 
        return res.status(400).json({ success: false, message: "invalid request" });
    }else{
      try {
        
        let { id } = req.query;

          const tasks = await prisma.task.findMany({
            where: {
              employeeId: Number(id),
            },
          });

        res.status(200).json({ success: true, data: tasks });
      } catch (error) {
        console.log(error);
        res.status(400).json({ success: false, error: error });
      }
    }
  }
