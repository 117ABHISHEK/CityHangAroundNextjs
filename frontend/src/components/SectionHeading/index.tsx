type SectionHeadingProps = {
  title: string;
  highlight?: string;
  description: string;
};

export default function SectionHeading({ title, highlight, description }: SectionHeadingProps) {
  return (
    <div className="section-head text-center max-w-3xl mx-auto mb-10">
      <h2 className="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-950">
        {title}{' '}
        {highlight ? <span className="text-red-600">{highlight}</span> : null}
      </h2>
      <p className="mt-4 text-base text-slate-500">{description}</p>
    </div>
  );
}
