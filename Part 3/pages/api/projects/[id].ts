import { NextApiRequest, NextApiResponse } from "next";
//change tables you wish to import
import { PrismaClient, User, Task, Project } from "@prisma/client";
import { decode, verify } from "jsonwebtoken";
const prisma = new PrismaClient();

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  if (req.method == "GET") {
    try {
      let { id } = req.query;

      const projects = await prisma.project.findMany({
        where: {
          id: Number(id),
        },
      });
      res.status(200).json({ success: true, data: projects });
    } catch (error) {
      res.status(400).json({ success: false, error: error });
    }
  } else if (req.method == "DELETE") {
    try {
      let { id } = req.query;

      const projects = await prisma.project.delete({
        where: {
          id: Number(id),
        },
      });
      res.status(200).json({ success: true, data: projects });
    } catch (error) {
      res.status(400).json({ success: false, error: error });
    }
  } else {
    return res.status(400).json({ success: false, message: "Invalid request" });
  }
}
