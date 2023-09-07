import { NextApiRequest, NextApiResponse } from "next";
import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

export default async function handler(
  req: NextApiRequest,
  res: NextApiResponse
) {
  const tasks = [
    {
      name: "Set up project 1 env",
      description: "Set up project 1 env",
      projectId: 1,
      employeeId: 1,
      status: "completed",
    },
    {
      name: "Set up project 2 env",
      description: "Set up project 2 env",
      projectId: 2,
      employeeId: 1,
      status: "completed",
    },
    {
      name: "start the beginning of the end",
      description: "start the beginning of the end desc",
      projectId: 3,
      employeeId: 1,
      status: "completed",
    },
    {
      name: "Do some admin",
      description: "Do some admin desc",
      projectId: 1,
      employeeId: 2,
      status: "inProgress",
    },
    {
      name: "Do some admin",
      description: "Do some admin desc",
      projectId: 2,
      employeeId: 2,
      status: "inProgress",
    },
  ];
  const tasksPrisma = await prisma.task.createMany({
    data: tasks,
  });
  res.status(200).json({ success: true, data: tasksPrisma });
}
