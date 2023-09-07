import { NextApiRequest, NextApiResponse } from "next";
//change tables you wish to import
import { PrismaClient, User, Task, Project } from "@prisma/client";
import { decode, verify } from "jsonwebtoken";
const prisma = new PrismaClient();

export default async function handler(
    req: NextApiRequest,
    res: NextApiResponse
  ) {
    if (req.method != "GET"){ 
        return res.status(400).json({ success: false, message: "invalid request" });
    }else{
      try {

          const users = await prisma.user.findMany({
          });
        res.status(200).json({ success: true, data: users });
      } catch (error) {
        res.status(400).json({ success: false, error: error });
      }
    }
  }